<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardGuruController extends Controller
{
    public function index()
    {
        $letter_requests = [
            [
                'id' => 1,
                'type' => 'Surat Perintah Tugas',
                'date' => '2025-08-25',
                'status' => 'approved',
                'approved_by' => 'KTU',
                'processed_by_tu' => true,
                // Data form surat
                'keperluan' => 'Menghadiri rapat koordinasi dengan dinas pendidikan provinsi untuk membahas kurikulum merdeka',
                'hari' => 'Senin',
                'tanggal' => '2025-08-25',
                'jam' => '09:00 - 15:00 WIB',
                'tempat' => 'Dinas Pendidikan Provinsi Jawa Timur, Surabaya',
                'guru_data' => [
                    [
                        'nama' => 'Ahmad Suryanto, S.Pd',
                        'nip' => '197801052006041002',
                        'keterangan' => 'Ketua Tim'
                    ],
                    [
                        'nama' => 'Siti Nurjanah, S.Pd',
                        'nip' => '198205102009042003',
                        'keterangan' => 'Anggota'
                    ]
                ],
                // Data pemohon
                'pemohon' => [
                    'nama' => 'Ahmad Suryanto, S.Pd',
                    'telp' => '081234567890',
                    'nip' => '197801052006041002'
                ]
            ],
            [
                'id' => 2,
                'type' => 'Surat Dispensasi',
                'date' => '2025-08-26',
                'status' => 'pending',
                'approved_by' => null,
                'processed_by_tu' => false,
                // Data form surat
                'keperluan' => 'Menghadiri acara pernikahan keluarga yang tidak dapat ditunda',
                'hari' => 'Selasa',
                'tanggal' => '2025-08-26',
                'jam' => '08:00 - 12:00 WIB',
                'tempat' => 'Gedung Serbaguna Malang',
                'guru_data' => [
                    [
                        'nama' => 'Budi Santoso, S.Pd',
                        'nip' => '198903152014041001',
                        'keterangan' => 'Pengajar'
                    ]
                ],
                // Data pemohon
                'pemohon' => [
                    'nama' => 'Budi Santoso, S.Pd',
                    'telp' => '081987654321',
                    'nip' => '198903152014041001'
                ]
            ]
        ];

        $letter_types = [
            'surat-perintah-tugas' => 'Surat Perintah Tugas',
            'surat-dispensasi' => 'Surat Dispensasi',
            //'surat-panggilan-ortu' => 'Surat Panggilan Orang Tua'
        ];

        return view('dashboard-guru', compact('letter_requests', 'letter_types'));
    }
}