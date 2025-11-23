<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\SuratDispensasi;
use App\Models\SuratPerintahTugas;
use App\Models\DetailDispensasi;
use App\Models\DetaiSpt;
use App\Models\Pengguna;
use App\Helpers\NotifikasiHelper;
use Illuminate\Support\Facades\Config;
use App\Models\Provinsi;
use App\Models\DinasSurat;
use App\Models\Sekolah;

class FormSuratController extends Controller
{
    public function index(Request $request)
    {
        $letter_types = [
            'dispensasi' => 'Surat Dispensasi',
            'spt' => 'Surat Perintah Tugas',
        ];

        $letter_type = $request->query('type', 'dispensasi');

        return view('form-surat', [
            'letter_types'    => $letter_types,
            'letter_type'     => $letter_type,
            'success_message' => session('success_message')
        ]);
    }

    // Route uses 'submit' in routes/web.php, keep compatibility by providing submit()
    public function submit(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:dispensasi,spt',
            'keperluan' => 'required|string',
            'tempat' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required|string',
            'hari' => 'required|string',
        ]);

        $user = Auth::user();

        // Determine jenis id (infer if not provided)
        $jenisId = null;
        if ($request->filled('id_jenis_surat')) {
            $jenisId = intval($request->input('id_jenis_surat'));
        } else {
            if ($request->type === 'dispensasi') $jenisId = 1;
            if ($request->type === 'spt') $jenisId = 2;
        }

        // Buat surat utama, simpan jenis/region. nomor_urut tetap null until approval
        $suratData = [
            'id_pengguna'   => $user->id_pengguna,
            'status_berkas' => 'diajukan',
        ];

        if (!is_null($jenisId)) $suratData['id_jenis_surat'] = $jenisId;
        // Fill regional IDs from request if provided, otherwise try to resolve ID by kode from config
        if ($request->filled('id_provinsi')) {
            $suratData['id_provinsi'] = $request->input('id_provinsi');
        } else {
            $kodeProv = Config::get('nomor_surat.provinsi_code');
            $provId = Provinsi::where('kode_provinsi', $kodeProv)->value('id');
            $suratData['id_provinsi'] = $provId ?? null;
        }

        if ($request->filled('id_dinas')) {
            $suratData['id_dinas'] = $request->input('id_dinas');
        } else {
            $kodeDinas = Config::get('nomor_surat.dinas_code');
            $dinasId = DinasSurat::where('kode_dinas', $kodeDinas)->value('id');
            $suratData['id_dinas'] = $dinasId ?? null;
        }

        if ($request->filled('id_sekolah')) {
            $suratData['id_sekolah'] = $request->input('id_sekolah');
        } else {
            $kodeSek = Config::get('nomor_surat.sekolah_code');
            $sekolahId = Sekolah::where('kode_sekolah', $kodeSek)->value('id');
            $suratData['id_sekolah'] = $sekolahId ?? null;
        }

        $surat = Surat::create($suratData);

        if ($request->type === 'dispensasi') {
            // Buat surat dispensasi
            $sd = SuratDispensasi::create([
                'id_surat'       => $surat->id_surat,
                'id_persetujuan' => null,
                'keperluan'      => $request->keperluan,
                'lampiran'       => $request->lampiran,
                'tempat'         => $request->tempat,
                'tanggal'        => $request->tanggal,
                'jam'            => $request->jam,
                'hari'           => $request->hari,
            ]);

            // Tambah detail siswa (jika ada)
            if ($request->has('siswa') && is_array($request->siswa)) {
                foreach ($request->siswa as $s) {
                    DetailDispensasi::create([
                        'id_sd'      => $sd->id_sd,
                        'nama_siswa' => $s['nama'] ?? '-',
                        'nisn'       => $s['nisn'] ?? '-',
                        'kelas'      => $s['kelas'] ?? '-',
                    ]);
                }
            }
        }

        if ($request->type === 'spt') {
            // Buat surat perintah tugas
            $spt = SuratPerintahTugas::create([
                'id_surat'       => $surat->id_surat,
                'id_persetujuan' => null,
                'keperluan'      => $request->keperluan,
                'lampiran'       => $request->lampiran,
                'tempat'         => $request->tempat,
                'tanggal'        => $request->tanggal,
                'jam'            => $request->jam,
                'hari'           => $request->hari,
            ]);

            // Tambah detail guru (jika ada)
            if ($request->has('guru') && is_array($request->guru)) {
                foreach ($request->guru as $g) {
                    DetaiSpt::create([
                        'id_spt'     => $spt->id_spt,
                        'nama_guru'  => $g['nama'] ?? '-',
                        'nip'        => $g['nip'] ?? '-',
                        'keterangan' => $g['keterangan'] ?? null,
                    ]);
                }
            }
        }

        // Kirim notifikasi ke Kepala Sekolah
        $kepsek = Pengguna::where('role', 'kepsek')->first();
        if ($kepsek) {
            NotifikasiHelper::insert(
                $surat->id_surat,
                $kepsek->id_pengguna,
                "Surat baru diajukan oleh {$user->nama}",
                null
            );
        }

        return redirect()->back()->with('success_message', 'Surat berhasil diajukan!');
    }

    // API untuk ambil data guru
    public function getGuruData()
    {
        // Prefer master guru table, but include pengguna with role GURU not present in guru table
        $fromGuru = \App\Models\Guru::select('nip', 'nama', 'no_telp as phone')->get();
        $nips = $fromGuru->pluck('nip')->filter()->unique()->toArray();

        $fromPengguna = Pengguna::where('role', 'GURU')
            ->whereNotIn('nip', $nips)
            ->select('nip', 'nama', 'no_telp as phone')
            ->get();

        $combined = $fromGuru->concat($fromPengguna)->values();
        return response()->json($combined);
    }

    // API untuk ambil data siswa (bisa disesuaikan kalau ada table siswa)
    public function getSiswaData()
    {
        // Sementara return kosong, nanti bisa disesuaikan kalau ada table siswa
        return response()->json([]);
    }
}