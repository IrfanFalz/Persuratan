<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persetujuan;
use App\Models\Notifikasi;
use Carbon\Carbon;

class DashboardKepsekController extends Controller
{
    public function index()
    {
        $pending_approval = Persetujuan::with([
                'surat.pengguna',
                'surat.suratDispensasi',
                'surat.suratPerintahTugas'
            ])
            ->whereNull('disetujui')
            ->get()
            ->map(function ($item) {
                $days = Carbon::parse($item->surat->dibuat_pada)->diffInDays(now());

                // Ambil keperluan (alasan) dari surat dispensasi atau SPT
                $reason = $item->surat->suratDispensasi->keperluan
                            ?? $item->surat->suratPerintahTugas->keperluan
                            ?? '-';

                return [
                    'id'            => $item->id_persetujuan,
                    'teacher'       => $item->surat->pengguna->nama ?? 'Guru tidak diketahui',
                    'nama_lengkap'  => $item->surat->pengguna->nama ?? 'Guru tidak diketahui',
                    'nip'           => $item->surat->pengguna->nip ?? '-',
                    'no_telp'       => $item->surat->pengguna->no_telp ?? '-',
                    'type'          => $item->surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'reason'        => $reason, // alias untuk keperluan
                    'keperluan'     => $reason,
                    'hari'          => $item->surat->suratDispensasi->hari
                                        ?? $item->surat->suratPerintahTugas->hari
                                        ?? '-',
                    'tanggal'       => $item->surat->dibuat_pada ?? '-',
                    'jam'           => $item->surat->suratDispensasi->jam
                                        ?? $item->surat->suratPerintahTugas->jam
                                        ?? '-',
                    'tempat'        => $item->surat->suratDispensasi->tempat
                                        ?? $item->surat->suratPerintahTugas->tempat
                                        ?? '-',
                    'duration'      => $days === 0 ? 'Baru diajukan' : $days . ' hari',
                    'date_requested'=> $item->surat->dibuat_pada,
                    'guru_data'     => [], // nanti bisa diisi relasi guru terkait
                ];
            });

        // Statistik bulanan
        $monthly_stats = [
            'total_letters'      => Persetujuan::count(),
            'approved_by_kepsek' => Persetujuan::where('disetujui', 'ya')->count(),
            'rejected'           => Persetujuan::where('disetujui', 'tidak')->count(),
            'pending'            => Persetujuan::whereNull('disetujui')->count(),
        ];

        // Aktivitas terbaru
        $recent_activities = Persetujuan::with('surat.pengguna')
            ->whereNotNull('disetujui')
            ->orderBy('timestamp', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $days = Carbon::parse($item->surat->dibuat_pada)->diffInDays(now());

                $reason = $item->surat->suratDispensasi->keperluan
                            ?? $item->surat->suratPerintahTugas->keperluan
                            ?? '-';

                return [
                    'id'       => $item->id_persetujuan,
                    'teacher'  => $item->surat->pengguna->nama ?? 'Guru tidak diketahui',
                    'nip'      => $item->surat->pengguna->nip ?? '-',
                    'no_telp'  => $item->surat->pengguna->no_telp ?? '-',
                    'type'     => $item->surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'reason'   => $reason,
                    'date'     => $item->timestamp,
                    'status'   => $item->disetujui,
                    'duration' => $days === 0 ? 'Baru diajukan' : $days . ' hari',
                ];
            });

        return view('dashboard-kepsek', compact('pending_approval', 'monthly_stats', 'recent_activities'))
            ->with('message', session('message'))
            ->with('status', session('status'));
    }

    public function approval(Request $request)
    {
        $request->validate([
            'id_persetujuan' => 'required|exists:persetujuan,id_persetujuan',
            'action'         => 'required|in:approve,reject',
        ]);

        $persetujuan = Persetujuan::with('surat.pengguna')->findOrFail($request->id_persetujuan);
        $teacher = $persetujuan->surat->pengguna->nama ?? 'Guru tidak diketahui';

        if ($request->action === 'approve') {
            $persetujuan->disetujui = 'ya';
            $message = "Surat dari {$teacher} berhasil disetujui dan diteruskan ke TU.";
            $status = 'success';
            $notifStatus = 'disetujui';
        } else {
            $persetujuan->disetujui = 'tidak';
            $message = "Surat dari {$teacher} ditolak. Notifikasi dikirim ke guru terkait.";
            $status = 'error';
            $notifStatus = 'ditolak';
        }

        $persetujuan->save();

        Notifikasi::create([
            'id_surat'    => $persetujuan->id_surat,
            'id_pengguna' => $persetujuan->surat->id_pengguna,
            'pesan'       => $message,
            'status'      => $notifStatus,
            'dibaca'      => null,
        ]);

        return redirect()->route('dashboard.kepsek')->with([
            'message' => $message,
            'status'  => $status,
        ]);
    }
}
