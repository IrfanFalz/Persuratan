<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardTuController extends Controller
{
    public function index()
    {
        $approved_letters = [
            [
                'id' => 1,
                'teacher' => 'Maya Sari',
                'nip' => '197801012005012001',
                'phone' => '081234567890',
                'full_name' => 'Maya Sari, S.Pd',
                'type' => 'Surat Tugas',
                'approved_date' => '2025-08-26',
                'approved_by' => 'Kepala Sekolah',
                'status' => 'approved',
                'keperluan' => 'Pelatihan Kurikulum Merdeka di Jakarta',
                'tempat' => 'Hotel Mercure Jakarta',
                'tanggal_tugas' => '2025-09-01',
                'hari' => 'Senin',
                'jam' => '08:00 - 16:00',
                'waktu' => '08:00 - 16:00',
                'guru_list' => [
                    [
                        'nama' => 'Maya Sari',
                        'nip' => '197801012005012001',
                        'keterangan' => 'Peserta Pelatihan'
                    ],
                    [
                        'nama' => 'Budi Santoso',
                        'nip' => '198203152009011003',
                        'keterangan' => 'Pendamping'
                    ]
                ]
            ],
            [
                'id' => 2,
                'teacher' => 'Eko Prasetyo',
                'nip' => '198205152008011002',
                'phone' => '081234567891',
                'full_name' => 'Eko Prasetyo, S.Pd, M.Pd',
                'type' => 'Surat Perintah Tugas',
                'approved_date' => '2025-08-25',
                'approved_by' => 'Kepala Sekolah',
                'status' => 'approved',
                'keperluan' => 'Mengawas Ujian Nasional CBT di SMP Negeri 2 Surabaya',
                'tempat' => 'SMP Negeri 2 Surabaya',
                'tanggal_tugas' => '2025-09-05',
                'hari' => 'Kamis',
                'jam' => '07:00 - 15:00',
                'waktu' => '07:00 - 15:00',
                'guru_list' => [
                    [
                        'nama' => 'Eko Prasetyo',
                        'nip' => '198205152008011002',
                        'keterangan' => 'Pengawas Utama'
                    ],
                    [
                        'nama' => 'Sri Wahyuni',
                        'nip' => '199012102015012002',
                        'keterangan' => 'Pengawas Cadangan'
                    ]
                ]
            ]
        ];

        // Semua completed letters otomatis pickup_notified = true
        $completed_letters = [
            [
                'id' => 3,
                'teacher' => 'Siti Nurjanah',
                'nip' => '198506152010012003',
                'phone' => '081234567892',
                'full_name' => 'Siti Nurjanah, S.Pd',
                'type' => 'Surat Dispensasi',
                'subject' => 'Bahasa Indonesia',
                'completed_date' => '2025-08-26 14:30:00',
                'status' => 'completed',
                'pickup_notified' => true, // Selalu true karena otomatis diberitahu saat selesai
                'keperluan' => 'Menghadiri pernikahan saudara kandung di Malang',
                'tempat' => 'Malang, Jawa Timur',
                'tanggal_tugas' => '2025-08-30',
                'hari' => 'Jumat',
                'jam' => '08:00 - 16:00',
                'guru_list' => [
                    [
                        'nama' => 'Siti Nurjanah',
                        'nip' => '198506152010012003',
                        'keterangan' => 'Yang memohon dispensasi'
                    ]
                ]
            ],
            [
                'id' => 4,
                'teacher' => 'Rahman Hakim',
                'nip' => '197912102006011001',
                'phone' => '081234567893',
                'full_name' => 'Rahman Hakim, S.Pd, M.Si',
                'type' => 'Surat Cuti',
                'subject' => 'Kimia',
                'completed_date' => '2025-08-25 16:45:00',
                'status' => 'completed',
                'pickup_notified' => true, // Selalu true karena otomatis diberitahu saat selesai
                'keperluan' => 'Cuti melahirkan istri',
                'tempat' => 'Rumah Sakit Dr. Soetomo Surabaya',
                'tanggal_tugas' => '2025-09-02',
                'hari' => 'Senin',
                'jam' => 'Seharian',
                'guru_list' => [
                    [
                        'nama' => 'Rahman Hakim',
                        'nip' => '197912102006011001',
                        'keterangan' => 'Yang mengajukan cuti'
                    ]
                ]
            ]
        ];

        $letters_indexed = collect($approved_letters)->keyBy('id')->toArray();
        $completed_letters_indexed = collect($completed_letters)->keyBy('id')->toArray();
        
        return view('dashboard-tu', compact('approved_letters', 'completed_letters', 'letters_indexed', 'completed_letters_indexed'))
            ->with('message', session('message'));
    }

    public function process(Request $request)
    {
        $action = $request->input('action');
        $letterId = $request->input('letter_id');

        $letters = [
            1 => ['teacher' => 'Maya Sari'],
            2 => ['teacher' => 'Eko Prasetyo'],
        ];

        $teacher = $letters[$letterId]['teacher'] ?? 'Guru tidak diketahui';

        if ($action === 'complete') {
            // Saat selesai, otomatis diberitahu
            $message = "Surat {$teacher} berhasil diselesaikan dan siap diambil. Notifikasi otomatis sudah dikirim ke guru yang bersangkutan.";
        } else {
            $message = "Aksi tidak dikenali untuk surat {$teacher}.";
        }

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    // Method untuk kirim ulang notifikasi
    public function resendNotification(Request $request)
    {
        $letterId = $request->input('letter_id');
        
        $letters = [
            3 => ['teacher' => 'Siti Nurjanah'],
            4 => ['teacher' => 'Rahman Hakim'],
        ];

        $teacher = $letters[$letterId]['teacher'] ?? 'Guru tidak diketahui';
        $message = "Notifikasi berhasil dikirim ulang ke {$teacher}. Reminder pengambilan surat sudah terkirim.";

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    public function getLetterDetail($id)
    {
        $letters = [
            1 => [
                'id' => 1,
                'teacher' => 'Maya Sari',
                'nip' => '197801012005012001',
                'phone' => '081234567890',
                'full_name' => 'Maya Sari, S.Pd',
                'type' => 'Surat Tugas',
                'subject' => 'Matematika',
                'approved_date' => '2025-08-26',
                'approved_by' => 'Kepala Sekolah',
                'status' => 'approved',
                'keperluan' => 'Pelatihan Kurikulum Merdeka di Jakarta',
                'tempat' => 'Hotel Mercure Jakarta',
                'tanggal_tugas' => '2025-09-01',
                'hari' => 'Senin',
                'jam' => '08:00 - 16:00',
                'guru_list' => [
                    [
                        'nama' => 'Maya Sari',
                        'nip' => '197801012005012001',
                        'keterangan' => 'Peserta Pelatihan'
                    ],
                    [
                        'nama' => 'Budi Santoso',
                        'nip' => '198203152009011003',
                        'keterangan' => 'Pendamping'
                    ]
                ]
            ],
            2 => [
                'id' => 2,
                'teacher' => 'Eko Prasetyo',
                'nip' => '198205152008011002',
                'phone' => '081234567891',
                'full_name' => 'Eko Prasetyo, S.Pd, M.Pd',
                'type' => 'Surat Perintah Tugas',
                'subject' => 'Fisika',
                'approved_date' => '2025-08-25',
                'approved_by' => 'Kepala Sekolah',
                'status' => 'approved',
                'keperluan' => 'Mengawas Ujian Nasional CBT di SMP Negeri 2 Surabaya',
                'tempat' => 'SMP Negeri 2 Surabaya',
                'tanggal_tugas' => '2025-09-05',
                'hari' => 'Kamis',
                'jam' => '07:00 - 15:00',
                'guru_list' => [
                    [
                        'nama' => 'Eko Prasetyo',
                        'nip' => '198205152008011002',
                        'keterangan' => 'Pengawas Utama'
                    ],
                    [
                        'nama' => 'Sri Wahyuni',
                        'nip' => '199012102015012002',
                        'keterangan' => 'Pengawas Cadangan'
                    ]
                ]
            ]
        ];

        return response()->json($letters[$id] ?? null);
    }
}