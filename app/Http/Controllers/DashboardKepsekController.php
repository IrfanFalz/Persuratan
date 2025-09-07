<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardKepsekController extends Controller
{
    public function index()
    {
        $pending_approval = [
            [
                'id' => 1,
                'teacher' => 'Maya Sari',
                'nip' => '197801012005012001',
                'subject' => 'Matematika',
                'type' => 'Surat Cuti',
                'reason' => 'Cuti melahirkan',
                'duration' => '3 bulan',
                'date_requested' => '2025-08-27',
                // Detail data pemohon
                'nama_lengkap' => 'Maya Sari, S.Pd',
                'no_telp' => '081234567890',
                // Detail surat
                'keperluan' => 'Permohonan cuti melahirkan sesuai dengan ketentuan yang berlaku. Saya memerlukan waktu istirahat untuk mempersiapkan kelahiran anak pertama dan masa pemulihan pasca melahirkan.',
                'hari' => 'Senin',
                'tanggal' => '2025-09-15',
                'jam' => '08:00 WIB',
                'tempat' => 'SMK Negeri 4 Malang',
                'guru_data' => [
                    [
                        'nama' => 'Dr. Siti Nurhaliza, M.Pd',
                        'nip' => '196505151990032001',
                        'keterangan' => 'Koordinator Bidang Akademik'
                    ],
                    [
                        'nama' => 'Drs. Ahmad Fauzi, M.M',
                        'nip' => '197203101998021003',
                        'keterangan' => 'Kepala Tata Usaha'
                    ]
                ]
            ]
        ];

        $monthly_stats = [
            'total_letters' => 45,
            'approved_by_kepsek' => 12,
            'approved_by_ktu' => 30,
            'rejected' => 0,
            'pending' => 1
        ];

        $recent_activities = [
            [
                'action' => 'approved',
                'teacher' => 'Eko Prasetyo',
                'type' => 'Surat Tugas',
                'date' => '2025-08-26 15:30:00'
            ]
        ];

        return view('dashboard-kepsek', compact('pending_approval', 'monthly_stats', 'recent_activities'))
               ->with('message', session('message'));
    }

    public function approval(Request $request)
    {
        $action = $request->input('action');
        $id = $request->input('request_id');

        $pending_approval = [
            1 => ['teacher' => 'Maya Sari'],
            2 => ['teacher' => 'Eko Prasetyo'],
        ];

        $teacher = $pending_approval[$id]['teacher'] ?? 'Guru tidak diketahui';

        if ($action === 'approve') {
            $message = "Surat dari {$teacher} berhasil disetujui dan diteruskan ke TU.";
        } else {
            $message = "Surat dari {$teacher} ditolak. Notifikasi dikirim ke guru terkait.";
        }

        return redirect()->route('dashboard.kepsek')->with('message', $message);
    }
}