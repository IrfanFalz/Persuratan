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

        // Buat surat utama
        $surat = Surat::create([
            'id_pengguna'   => $user->id_pengguna,
            'status_berkas' => 'diajukan' // Status awal: diajukan
        ]);

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
        $guru = Pengguna::where('role', 'guru')
            ->select('nip', 'nama', 'no_telp as phone')
            ->get();

        return response()->json($guru);
    }

    // API untuk ambil data siswa (bisa disesuaikan kalau ada table siswa)
    public function getSiswaData()
    {
        // Sementara return kosong, nanti bisa disesuaikan kalau ada table siswa
        return response()->json([]);
    }
}