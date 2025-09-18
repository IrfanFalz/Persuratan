<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormSuratController extends Controller
{
    public function index(Request $request)
    {
        $letter_types = [
            'surat-keterangan' => 'Surat Keterangan',
            'surat-panggilan-ortu' => 'Surat Panggilan Orang Tua',
            'surat-dispensasi' => 'Surat Dispensasi',
            'surat-perintah-tugas' => 'Surat Perintah Tugas',
        ];

        $letter_type = $request->query('type', 'surat-keterangan');

        return view('form-surat', [
            'letter_types' => $letter_types,
            'letter_type' => $letter_type,
            'success_message' => session('success_message')
        ]);
    }

    public function submit(Request $request)
    {
        return redirect()->route('form.surat', ['type' => $request->input('letter_type')])
                         ->with('success_message', 'Permintaan surat berhasil diajukan dan akan diproses oleh KTU.');
    }

    public function getGuruData()
    {
        $guru_data = [
            ['nip' => '196501011990031001', 'nama' => 'Dr. Ahmad Suryadi, S.Pd., M.Pd.', 'phone' => '081234567890'],
            ['nip' => '197203051995122001', 'nama' => 'Siti Nurhalimah, S.Pd., M.Si.', 'phone' => '081234567891'],
            ['nip' => '198008101998032002', 'nama' => 'Dewi Kartika, S.Pd., M.Pd.', 'phone' => '081234567892'],
            ['nip' => '197905152003121003', 'nama' => 'Budi Santoso, S.Pd., M.M.', 'phone' => '081234567893'],
            ['nip' => '198512202009012004', 'nama' => 'Rina Marlina, S.Pd., M.Pd.', 'phone' => '081234567894'],
            ['nip' => '197701151999031005', 'nama' => 'Muhammad Ridwan, S.Pd., M.Si.', 'phone' => '081234567895'],
            ['nip' => '198906252014032006', 'nama' => 'Tri Wahyuni, S.Pd., M.Pd.', 'phone' => '081234567896'],
            ['nip' => '197812102001121007', 'nama' => 'Agus Firmansyah, S.Pd., M.M.', 'phone' => '081234567897'],
            ['nip' => '199002282015032008', 'nama' => 'Lestari Handayani, S.Pd., M.Pd.', 'phone' => '081234567898'],
            ['nip' => '198304052006041009', 'nama' => 'Dedi Kurniawan, S.Pd., M.Si.', 'phone' => '081234567899']
        ];
        
        return response()->json($guru_data);
    }

    public function getSiswaData()
    {
        $siswa_data = [
            ['nama' => 'Andi Pratama', 'nisn' => '0051234567', 'kelas' => 'XII RPL 1'],
            ['nama' => 'Budi Santoso', 'nisn' => '0051234568', 'kelas' => 'XII RPL 2'],
            ['nama' => 'Citra Dewi', 'nisn' => '0051234569', 'kelas' => 'XI TKJ 1'],
            ['nama' => 'Dina Marlina', 'nisn' => '0051234570', 'kelas' => 'XI TKJ 2'],
            ['nama' => 'Eko Wijaya', 'nisn' => '0051234571', 'kelas' => 'X MM 1'],
            ['nama' => 'Fitri Handayani', 'nisn' => '0051234572', 'kelas' => 'X MM 2'],
            ['nama' => 'Gilang Ramadhan', 'nisn' => '0051234573', 'kelas' => 'XII RPL 1'],
            ['nama' => 'Hani Safitri', 'nisn' => '0051234574', 'kelas' => 'XI TKJ 1'],
            ['nama' => 'Ivan Setiawan', 'nisn' => '0051234575', 'kelas' => 'X MM 1'],
            ['nama' => 'Jihan Putri', 'nisn' => '0051234576', 'kelas' => 'XII RPL 2']
        ];
        
        return response()->json($siswa_data);
    }
}