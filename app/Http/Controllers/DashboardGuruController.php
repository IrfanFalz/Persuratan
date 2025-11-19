<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Notifikasi;

class DashboardGuruController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $letter_requests = Surat::with([
                'persetujuan',
                'pengguna',
                'suratDispensasi.detail',
                'suratPerintahTugas.detail'
            ])
            ->where('id_pengguna', $userId)
            ->orderBy('dibuat_pada', 'desc')
            ->get()
            ->map(function ($surat) {

                /**
                 * NORMALISASI STATUS BERDASARKAN DATABASE
                 * ----------------------------------------------------
                 * pending      = baru diajukan
                 * disetujui    = kepsek approve → TU proses
                 * ditolak      = kepsek tolak
                 * selesai      = TU klik selesai
                 */
                $status = match ($surat->status_berkas) {
                    'pending'   => 'pending',
                    'disetujui' => 'approved',
                    'ditolak'   => 'declined',
                    'selesai'   => 'done',
                    default     => 'pending'
                };

                /**
                 * PROGRESS BAR 3 STEP
                 * ----------------------------------------------------
                 * 1 = pending
                 * 2 = approved / declined
                 * 3 = done
                 */
                $progress = match ($status) {
                    'pending'  => 1,
                    'approved' => 2,
                    'declined' => 2,
                    'done'     => 3,
                    default    => 1
                };

                /**
                 * LIST DETAIL GURU
                 */
                if ($surat->suratDispensasi) {
                    $guruData = $surat->suratDispensasi->detail->map(function ($d) {
                        return (object)[
                            'nama'       => $d->nama ?? '-',
                            'nip'        => $d->nip ?? '-',
                            'keterangan' => $d->keterangan ?? '-',
                        ];
                    });
                } elseif ($surat->suratPerintahTugas) {
                    $guruData = $surat->suratPerintahTugas->detail->map(function ($d) {
                        return (object)[
                            'nama'       => $d->nama_guru ?? '-',
                            'nip'        => $d->nip ?? '-',
                            'keterangan' => $d->keterangan ?? '-',
                        ];
                    });
                } else {
                    $guruData = collect([]);
                }

                return (object)[
                    'id'        => $surat->id_surat,
                    'type'      => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'date'      => $surat->dibuat_pada,

                    'status'    => $status,
                    'progress'  => $progress,

                    'approved_by'       => optional($surat->persetujuan)->id_pengguna,
                    'processed_by_tu'   => $status === 'done',

                    'keperluan' => optional($surat->suratDispensasi)->keperluan
                                   ?? optional($surat->suratPerintahTugas)->keperluan
                                   ?? '-',

                    'hari' => optional($surat->suratDispensasi)->hari
                              ?? optional($surat->suratPerintahTugas)->hari
                              ?? '-',

                    'tanggal' => optional($surat->suratDispensasi)->tanggal
                                   ?? optional($surat->suratPerintahTugas)->tanggal
                                   ?? '-',

                    'jam' => optional($surat->suratDispensasi)->jam
                               ?? optional($surat->suratPerintahTugas)->jam
                               ?? '-',

                    'tempat' => optional($surat->suratDispensasi)->tempat
                                   ?? optional($surat->suratPerintahTugas)->tempat
                                   ?? '-',

                    'pemohon' => (object)[
                        'nama' => $surat->pengguna->nama ?? '-',
                        'telp' => $surat->pengguna->no_telp ?? '-',
                        'nip'  => $surat->pengguna->nip ?? '-',
                    ],

                    'guru_data' => $guruData
                ];
            });

        // COUNT SUMMARY
        $approvedCount = $letter_requests->where('status', 'approved')->count();
        $declinedCount = $letter_requests->where('status', 'declined')->count();
        $doneCount     = $letter_requests->where('status', 'done')->count();
        $pendingCount  = $letter_requests->where('status', 'pending')->count();
        $totalCount    = $letter_requests->count();

        // NOTIFIKASI GURU
        $notifikasi = Notifikasi::where('id_pengguna', $userId)
            ->latest('created_at')
            ->take(5)
            ->get();

        $letter_types = [
            'surat-perintah-tugas' => 'Surat Perintah Tugas',
            'surat-dispensasi'     => 'Surat Dispensasi',
        ];

        return view('dashboard-guru', compact(
            'letter_requests',
            'letter_types',
            'notifikasi',
            'approvedCount',
            'declinedCount',
            'doneCount',
            'pendingCount',
            'totalCount'
        ));
    }
}
