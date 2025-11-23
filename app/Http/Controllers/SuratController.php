<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Persetujuan;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\Kategori;
use App\Models\SuratDispensasi;
use App\Models\SuratPerintahTugas;
use App\Models\DetailDispensasi;
use App\Models\DetailSpt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\SuratCreated;
use App\Events\SuratStatusUpdated;
use App\Events\NotifikasiCreated;
use App\Models\TemplateSurat;

class SuratController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $surat = Surat::with(['persetujuan', 'suratDispensasi', 'suratPerintahTugas'])
                ->where('id_pengguna', $user->id_pengguna)
                ->orderByDesc('dibuat_pada')
                ->get();
        } else {
            $surat = Surat::with(['persetujuan', 'pengguna'])
                ->orderByDesc('dibuat_pada')
                ->get();
        }

        $notifikasi = Notifikasi::where('id_pengguna', $user->id_pengguna)
            ->orderByDesc('dibuat_pada')
            ->get();

        return view('guru.dashboard', compact('surat', 'notifikasi'));
    }

    /**
     * Try to parse various user-provided jam formats into SQL TIME (H:i:s).
     * Returns null if unable to parse.
     */
    protected function parseJam($jam)
    {
        if (empty($jam)) return null;

        // Normalize common separators
        $s = str_replace('.', ':', $jam);

        // Find first occurrence of HH:MM or HH:MM:SS
        if (preg_match('/\b(\d{1,2}:\d{2}(?::\d{2})?)\b/', $s, $m)) {
            $time = $m[1];
            if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
                $time .= ':00';
            }
            $parts = explode(':', $time);
            $h = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            $i = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
            $sec = isset($parts[2]) ? str_pad($parts[2], 2, '0', STR_PAD_LEFT) : '00';
            return "{$h}:{$i}:{$sec}";
        }

        // Fallback to strtotime if possible
        $ts = strtotime($jam);
        if ($ts !== false) {
            return date('H:i:s', $ts);
        }

        Log::warning('Unable to parse jam value: '.$jam);
        return null;
    }

    public function create()
    {
        return view('surat.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'jenis'      => 'required|in:spt,dispensasi',
            'keperluan'  => 'required|string',
            'tempat'     => 'required|string',
            'hari'       => 'required|string',
            'tanggal'    => 'required|date',
            'jam'        => 'required|string',
            'lampiran'   => 'nullable|file|max:5120',

            // GURU (SPT)
            'nama_guru.*'        => 'nullable|string',
            'nip_guru.*'         => 'nullable|string',
            'keterangan_guru.*'  => 'nullable|string',

            // SISWA (DISPENSASI)
            'nama_siswa.*'       => 'nullable|string',
            'nisn.*'             => 'nullable|string',
            'kelas_siswa.*'      => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Determine template id: prefer request-provided id_template when valid,
            // otherwise attempt to lookup by jenis (tipe)
            $templateId = null;
            try {
                if ($request->filled('id_template')) {
                    $t = TemplateSurat::find($request->input('id_template'));
                    if ($t) {
                        $templateId = $t->id;
                    }
                }

                if (is_null($templateId)) {
                    $template = TemplateSurat::where('tipe', $request->jenis)->first();
                    $templateId = $template ? $template->id : null;
                }
            } catch (\Exception $e) {
                // If lookup fails, keep null (id_template is nullable) and log
                Log::warning('Unable to resolve template for jenis '.$request->jenis.': '.$e->getMessage());
            }
            // 1. SURAT
            $surat = Surat::create([
                'id_pengguna'   => $user->id_pengguna,
                'id_template'   => $templateId,
                'status_berkas' => 'pending',
                'dibuat_pada'   => now(),
            ]);

            // 2. PERSETUJUAN
            $persetujuan = Persetujuan::create([
                'id_surat'    => $surat->id_surat,
                'id_pengguna' => $user->id_pengguna,
                'disetujui'   => null,
                'timestamp'   => now(),
            ]);

            $surat->update(['id_persetujuan' => $persetujuan->id_persetujuan]);

            // 3. KATEGORI
            Kategori::create(['id_surat' => $surat->id_surat]);

            // 4. LAMPIRAN
            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $lampiranPath = $request->file('lampiran')->store('lampiran_surat');
            }

            /* =============================
                 JENIS SURAT
               ============================= */

            /* =============================
                SURAT DISPENSASI
            ============================= */
            if ($request->jenis === 'dispensasi') {

                $disp = SuratDispensasi::create([
                    'id_surat'       => $surat->id_surat,
                    'id_persetujuan' => $persetujuan->id_persetujuan,
                    'keperluan'      => $request->keperluan,
                    'tempat'         => $request->tempat,
                    'tanggal'        => $request->tanggal,
                    'jam'            => $this->parseJam($request->jam),
                    'hari'           => $request->hari,
                    'lampiran'       => $lampiranPath,
                ]);

                // Ambil array siswa
                $namaArr  = $request->input('nama_siswa', []);
                $nisnArr  = $request->input('nisn', []);
                $kelasArr = $request->input('kelas_siswa', []);

                // Loop berdasarkan NISN agar tidak Undefined key
                foreach ($nisnArr as $i => $nisn) {
                    if (empty($nisn)) continue;

                    DetailDispensasi::create([
                        'id_sd'      => $disp->id_sd,
                        'nisn'       => $nisn,
                        'nama_siswa' => $namaArr[$i] ?? null,
                        'kelas'      => $kelasArr[$i] ?? null,
                    ]);
                }
            }

            /* =============================
                   SURAT PERINTAH TUGAS
               ============================= */
            if ($request->jenis === 'spt') {

                $spt = SuratPerintahTugas::create([
                    'id_surat'       => $surat->id_surat,
                    'id_persetujuan' => $persetujuan->id_persetujuan,
                    'keperluan'      => $request->keperluan,
                    'tempat'         => $request->tempat,
                    'tanggal'        => $request->tanggal,
                    'jam'            => $this->parseJam($request->jam),
                    'hari'           => $request->hari,
                    'lampiran'       => $lampiranPath,
                ]);

                // Ambil array guru
                $namaGuruArr = $request->nama_guru ?? [];
                $nipGuruArr  = $request->nip_guru ?? [];
                $ketGuruArr  = $request->keterangan_guru ?? [];

                $countGuru = max(count($namaGuruArr), count($nipGuruArr), count($ketGuruArr));

                for ($i = 0; $i < $countGuru; $i++) {
                    if (empty($namaGuruArr[$i]) && empty($nipGuruArr[$i]) && empty($ketGuruArr[$i])) {
                        continue;
                    }

                    DetailSpt::create([
                        'id_spt'     => $spt->id_spt,
                        'nama_guru'  => $namaGuruArr[$i] ?? null,
                        'nip'        => $nipGuruArr[$i] ?? null,
                        'keterangan' => $ketGuruArr[$i] ?? null,
                    ]);
                }
            }

            // 6. NOTIFIKASI
            $roles = ['TU', 'KEPSEK', 'ADMIN'];
            $receivers = Pengguna::whereIn(DB::raw('UPPER(role)'), $roles)->get();

            foreach ($receivers as $r) {
                $notif = Notifikasi::create([
                    'id_pengguna' => $r->id_pengguna,
                    'id_surat'    => $surat->id_surat,
                    'pesan'       => "Surat baru dari {$user->nama}.",
                    'status_baca' => 0,
                    'dibuat_pada' => now(),
                ]);

                broadcast(new NotifikasiCreated($notif))->toOthers();
            }

            DB::commit();

            broadcast(new SuratCreated($surat))->toOthers();

            return redirect()->route('dashboard.guru')
                ->with('success', 'Surat berhasil diajukan dan menunggu persetujuan.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Gagal mengajukan surat: '.$e->getMessage(), [
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengajukan surat: '.$e->getMessage());
        }
    }
}
