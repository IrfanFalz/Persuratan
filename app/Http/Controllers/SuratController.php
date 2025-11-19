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
            // 1. SURAT
            $surat = Surat::create([
                'id_pengguna'   => $user->id_pengguna,
                'id_template'   => null,
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
                    'jam'            => $request->jam,
                    'hari'           => $request->hari,
                    'lampiran'       => $lampiranPath,
                ]);

                // Ambil array siswa (sesuai Blade)
                $namaArr  = $request->nama_siswa ?? [];
                $nisnArr  = $request->nisn ?? [];
                $kelasArr = $request->kelas_siswa ?? [];

                $count = max(count($namaArr), count($nisnArr), count($kelasArr));

                for ($i = 0; $i < $count; $i++) {
                    if (empty($namaArr[$i]) && empty($nisnArr[$i]) && empty($kelasArr[$i])) {
                        continue;
                    }

                    DetailDispensasi::create([
                        'id_sd'      => $disp->id_sd,
                        'nama_siswa' => $namaArr[$i] ?? null,
                        'nisn'       => $nisnArr[$i] ?? null,
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
                    'jam'            => $request->jam,
                    'hari'           => $request->hari,
                    'lampiran'       => $lampiranPath,
                ]);

                // Ambil array guru (sesuai Blade)
                $namaGuruArr = $request->nama_guru ?? [];
                $nipGuruArr  = $request->nip_guru ?? [];
                $ketGuruArr  = $request->keterangan_guru ?? [];

                $countGuru = max(count($namaGuruArr), count($nipGuruArr), count($ketGuruArr));

                for ($i = 0; $i < $countGuru; $i++) {

                    if (empty($namaGuruArr[$i]) &&
                        empty($nipGuruArr[$i]) &&
                        empty($ketGuruArr[$i])) {
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
