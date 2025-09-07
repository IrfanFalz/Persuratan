<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardKtuController extends Controller
{
    public function index()
    {
        $pending_requests = [
            [
                'id' => 1,
                'teacher' => 'Maya Sari',
                'nip' => '197801012005012001',
                'type' => 'Surat Izin',
                'subject' => 'Matematika',
                'date_requested' => '2025-08-27 09:30:00',
                'reason' => 'Keperluan keluarga mendadak',
                'status' => 'pending',
                // Detail data pemohon
                'full_name' => 'Maya Sari, S.Pd',
                'phone' => '081234567890',
                // Detail surat
                'keperluan' => 'Menghadiri acara pernikahan keponakan yang berlokasi di luar kota. Diperlukan izin untuk tidak hadir mengajar pada hari yang disebutkan.',
                'hari' => 'Jumat',
                'tanggal' => '2025-08-30',
                'jam' => '07:00 - 12:00 WIB',
                'tempat' => 'SMK Negeri 4 Malang',
                'guru_data' => [
                    [
                        'nama' => 'Maya Sari',
                        'nip' => '197801012005012001',
                        'keterangan' => 'Pemohon'
                    ],
                    [
                        'nama' => 'Budi Santoso',
                        'nip' => '198503152010011003',
                        'keterangan' => 'Guru Pengganti'
                    ]
                ]
            ],
            [
                'id' => 2,
                'teacher' => 'Eko Prasetyo',
                'nip' => '198205152008011002',
                'type' => 'Surat Tugas',
                'subject' => 'Fisika',
                'date_requested' => '2025-08-27 08:15:00',
                'reason' => 'Pelatihan Kurikulum Merdeka di Jakarta',
                'status' => 'pending',
                // Detail data pemohon
                'full_name' => 'Eko Prasetyo, S.Pd., M.Pd',
                'phone' => '081298765432',
                // Detail surat
                'keperluan' => 'Mengikuti Workshop dan Pelatihan Implementasi Kurikulum Merdeka untuk Mata Pelajaran Fisika yang diselenggarakan oleh Kemendikbud Ristek.',
                'hari' => 'Senin - Rabu',
                'tanggal' => '2025-09-02',
                'jam' => '08:00 - 16:00 WIB',
                'tempat' => 'Hotel Grand Kemang, Jakarta Selatan',
                'guru_data' => [
                    [
                        'nama' => 'Eko Prasetyo',
                        'nip' => '198205152008011002',
                        'keterangan' => 'Peserta Pelatihan'
                    ],
                    [
                        'nama' => 'Sari Dewi',
                        'nip' => '199201082018012001',
                        'keterangan' => 'Pendamping/Observer'
                    ],
                    [
                        'nama' => 'Ahmad Fauzi',
                        'nip' => '198812102015011001',
                        'keterangan' => 'Guru Pengganti'
                    ]
                ]
            ]
        ];

        $approved_requests = [
            [
                'id' => 3,
                'teacher' => 'Siti Nurjanah',
                'type' => 'Surat Keterangan',
                'date_approved' => '2025-08-26',
                'status' => 'approved',
                'forwarded_to_tu' => true
            ]
        ];

        return view('dashboard-ktu', compact('pending_requests', 'approved_requests'))
               ->with('message', session('message'));
    }

    public function approval(Request $request)
    {
        $action = $request->input('action');
        $id = $request->input('request_id');

        $pending_requests = [
            1 => ['teacher' => 'Maya Sari'],
            2 => ['teacher' => 'Eko Prasetyo'],
        ];

        $teacher = $pending_requests[$id]['teacher'] ?? 'Guru tidak diketahui';

        if ($action === 'approve') {
            $message = "Surat dari {$teacher} berhasil disetujui dan diteruskan ke Kepala Sekolah.";
        } else {
            $message = "Surat dari {$teacher} ditolak oleh KTU. Notifikasi dikirim ke guru terkait.";
        }

        return redirect()->route('dashboard.ktu')->with('message', $message);
    }
}