<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratDispensasi;
use App\Models\SuratPerintahTugas;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Helpers\NotifikasiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardTuController extends Controller
{
    public function index()
    {
        // SURAT YANG SUDAH DISETUJUI KEPSEK (status: approved)
        $approved_letters = Surat::with(['pengguna', 'suratDispensasi', 'suratPerintahTugas.detail'])
            ->whereIn('status_berkas', ['approved', 'disetujui', 'ya', 'approve', 'diproses'])
            ->orderBy('dibuat_pada', 'desc')
            ->get()
            ->map(function ($surat) {
                $detail = $surat->suratDispensasi ?? $surat->suratPerintahTugas;

                // Skip jika tidak ada detail
                if (!$detail) {
                    \Log::warning("Surat ID {$surat->id_surat} tidak memiliki detail");
                    return null;
                }

                $rendered = null;
                // Jika ada template untuk surat ini, coba render dengan placeholder sederhana.
                if ($surat->template && $surat->template->isi_template) {
                    $tmpl = $surat->template->isi_template;

                    // Bangun tabel guru jika tersedia
                    $guruRows = '';
                    $guruList = [];
                    if ($surat->suratPerintahTugas) {
                        $details = $surat->suratPerintahTugas->detail ?? collect();
                        foreach ($details as $idx => $d) {
                            $guruRows .= '<tr><td class="py-1">'.($idx+1).'</td><td class="py-1">'.($d->nama_guru ?? '-').'</td><td class="py-1">'.($d->nip ?? '-').'</td><td class="py-1">'.($d->keterangan ?? '-').'</td></tr>';
                            $guruList[] = $d;
                        }
                    }
                    if (empty($guruRows) && $surat->suratDispensasi) {
                        // untuk dispensasi tampilkan pemohon sebagai satu baris
                        $guruRows = '<tr><td class="py-1">1</td><td class="py-1">'.($surat->pengguna->nama ?? '-').'</td><td class="py-1">'.($surat->pengguna->nip ?? '-').'</td><td class="py-1">Yang mengajukan</td></tr>';
                    }

                    $guruTable = '<table class="w-full border-collapse" style="border:1px solid #ddd">'
                                .'<thead><tr><th class="py-1">No.</th><th class="py-1">Nama</th><th class="py-1">NIP</th><th class="py-1">Keterangan</th></tr></thead>'
                                .'<tbody>'.$guruRows.'</tbody></table>';

                    $replacements = [
                        '{{nomor}}' => $surat->nomor_surat ?? '—',
                        '{{dasar}}' => $detail->dasar ?? ($surat->keterangan ?? '-'),
                        '{{keperluan}}' => $detail->keperluan ?? '-'. '',
                        '{{tanggal_tugas}}' => $detail->tanggal ?? '-'. '',
                        '{{tempat_tugas}}' => $detail->tempat ?? '-',
                        '{{waktu_tugas}}' => $detail->jam ?? '-',
                        '{{tanggal_surat}}' => now()->format('d F Y'),
                        '{{nama_kepala_sekolah}}' => config('app.kepala_nama', 'Kepala Sekolah'),
                        '{{nip_kepala_sekolah}}' => config('app.kepala_nip', ''),
                        '{{guru_table}}' => $guruTable,
                    ];

                    $rendered = strtr($tmpl, $replacements);
                }

                return [
                    'id' => $surat->id_surat,
                    'teacher' => $surat->pengguna->nama,
                    'nip' => $surat->pengguna->nip,
                    'phone' => $surat->pengguna->no_telp,
                    'full_name' => $surat->pengguna->nama,
                    'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'approved_date' => $surat->dibuat_pada,
                    'approved_by' => 'Kepala Sekolah',
                    'status' => 'approved',
                    'keperluan' => $detail->keperluan ?? '-',
                    'tempat' => $detail->tempat ?? '-',
                    'tanggal_tugas' => $detail->tanggal ?? '-',
                    'hari' => $detail->hari ?? '-',
                    'jam' => $detail->jam ?? '-',
                    'waktu' => $detail->jam ?? '-',
                    'guru_list' => $this->getGuruList($surat),
                    'rendered_template' => $rendered,
                ];
            })
            ->filter() // Hapus null values
            ->values(); // Re-index array

        // SURAT YANG SUDAH SELESAI (status: completed)
        $completed_letters = Surat::with(['pengguna', 'suratDispensasi', 'suratPerintahTugas.detail'])
            ->whereIn('status_berkas', ['completed', 'done', 'selesai'])
            ->orderBy('dibuat_pada', 'desc')
            ->get()
            ->map(function ($surat) {
                $detail = $surat->suratDispensasi ?? $surat->suratPerintahTugas;

                if (!$detail) return null;
                $rendered = null;
                if ($surat->template && $surat->template->isi_template) {
                    $tmpl = $surat->template->isi_template;

                    $guruRows = '';
                    if ($surat->suratPerintahTugas) {
                        $details = $surat->suratPerintahTugas->detail ?? collect();
                        foreach ($details as $idx => $d) {
                            $guruRows .= '<tr><td class="py-1">'.($idx+1).'</td><td class="py-1">'.($d->nama_guru ?? '-').'</td><td class="py-1">'.($d->nip ?? '-').'</td><td class="py-1">'.($d->keterangan ?? '-').'</td></tr>';
                        }
                    }
                    if (empty($guruRows) && $surat->suratDispensasi) {
                        $guruRows = '<tr><td class="py-1">1</td><td class="py-1">'.($surat->pengguna->nama ?? '-').'</td><td class="py-1">'.($surat->pengguna->nip ?? '-').'</td><td class="py-1">Yang mengajukan</td></tr>';
                    }

                    $guruTable = '<table class="w-full border-collapse" style="border:1px solid #ddd">'
                                .'<thead><tr><th class="py-1">No.</th><th class="py-1">Nama</th><th class="py-1">NIP</th><th class="py-1">Keterangan</th></tr></thead>'
                                .'<tbody>'.$guruRows.'</tbody></table>';

                    $replacements = [
                        '{{nomor}}' => $surat->nomor_surat ?? '—',
                        '{{dasar}}' => $detail->dasar ?? ($surat->keterangan ?? '-'),
                        '{{keperluan}}' => $detail->keperluan ?? '-'. '',
                        '{{tanggal_tugas}}' => $detail->tanggal ?? '-'. '',
                        '{{tempat_tugas}}' => $detail->tempat ?? '-',
                        '{{waktu_tugas}}' => $detail->jam ?? '-',
                        '{{tanggal_surat}}' => now()->format('d F Y'),
                        '{{nama_kepala_sekolah}}' => config('app.kepala_nama', 'Kepala Sekolah'),
                        '{{nip_kepala_sekolah}}' => config('app.kepala_nip', ''),
                        '{{guru_table}}' => $guruTable,
                    ];

                    $rendered = strtr($tmpl, $replacements);
                }

                return [
                    'id' => $surat->id_surat,
                    'teacher' => $surat->pengguna->nama,
                    'nip' => $surat->pengguna->nip,
                    'phone' => $surat->pengguna->no_telp,
                    'full_name' => $surat->pengguna->nama,
                    'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'completed_date' => $surat->dibuat_pada,
                    'status' => 'completed',
                    'pickup_notified' => true,
                    'keperluan' => $detail->keperluan ?? '-',
                    'tempat' => $detail->tempat ?? '-',
                    'tanggal_tugas' => $detail->tanggal ?? '-',
                    'hari' => $detail->hari ?? '-',
                    'jam' => $detail->jam ?? '-',
                    'guru_list' => $this->getGuruList($surat),
                    'rendered_template' => $rendered,
                ];
            })
            ->filter()
            ->values();

        $letters_indexed = $approved_letters->keyBy('id')->toArray();
        $completed_letters_indexed = $completed_letters->keyBy('id')->toArray();

        return view('dashboard-tu', compact(
            'approved_letters',
            'completed_letters',
            'letters_indexed',
            'completed_letters_indexed'
        ));
    }

    // Helper function untuk ambil list guru
    private function getGuruList($surat)
    {
        $list = [];

        if ($surat->suratPerintahTugas) {
            $details = $surat->suratPerintahTugas->detail ?? collect();
            
            foreach ($details as $detail) {
                $list[] = [
                    'nama' => $detail->nama_guru ?? '-',
                    'nip' => $detail->nip ?? '-',
                    'keterangan' => $detail->keterangan ?? '-',
                ];
            }
        }
        
        // Jika tidak ada detail atau untuk dispensasi, tampilkan guru yang mengajukan
        if (empty($list)) {
            $list[] = [
                'nama' => $surat->pengguna->nama,
                'nip' => $surat->pengguna->nip,
                'keterangan' => 'Yang mengajukan',
            ];
        }

        return $list;
    }

    // Proses surat (selesaikan surat)
    public function process(Request $request)
    {
        $action = $request->input('action');
        $letterId = $request->input('letter_id');

        $surat = Surat::with('pengguna')->findOrFail($letterId);
        $teacher = $surat->pengguna->nama;

        if ($action === 'complete') {
            // Update status jadi completed
            $surat->update(['status_berkas' => 'completed']);

            // Generate nomor surat
            try {
                \App\Helpers\NomorSuratHelper::generate($surat);
            } catch (\Exception $e) {
                \Log::error('Failed to generate nomor surat: '.$e->getMessage());
            }

            // Kirim notifikasi otomatis ke guru
            NotifikasiHelper::insert(
                $surat->id_surat,
                $surat->id_pengguna,
                "Surat Anda sudah selesai dan siap diambil di TU",
                null
            );

            $message = "Surat {$teacher} berhasil diselesaikan dan siap diambil. Notifikasi otomatis sudah dikirim.";
        } else {
            $message = "Aksi tidak dikenali.";
        }

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    // Kirim ulang notifikasi
    public function resendNotification(Request $request)
    {
        $letterId = $request->input('letter_id');

        $surat = Surat::with('pengguna')->findOrFail($letterId);
        $teacher = $surat->pengguna->nama;

        // Kirim notifikasi ulang
        NotifikasiHelper::insert(
            $surat->id_surat,
            $surat->id_pengguna,
            "Reminder: Surat Anda sudah selesai dan menunggu pengambilan di TU",
            null
        );

        $message = "Notifikasi berhasil dikirim ulang ke {$teacher}.";

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    // Get detail surat (untuk modal/preview)
    public function getLetterDetail($id)
    {
        $surat = Surat::with(['pengguna', 'suratDispensasi', 'suratPerintahTugas.detailSpt'])
            ->findOrFail($id);

        $detail = $surat->suratDispensasi ?? $surat->suratPerintahTugas;

        $data = [
            'id' => $surat->id_surat,
            'teacher' => $surat->pengguna->nama,
            'nip' => $surat->pengguna->nip,
            'phone' => $surat->pengguna->no_telp,
            'full_name' => $surat->pengguna->nama,
            'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
            'status' => $surat->status_berkas,
            'keperluan' => $detail->keperluan ?? '-',
            'tempat' => $detail->tempat ?? '-',
            'tanggal_tugas' => $detail->tanggal ?? '-',
            'hari' => $detail->hari ?? '-',
            'jam' => $detail->jam ?? '-',
            'guru_list' => $this->getGuruList($surat),
        ];

        return response()->json($data);
    }
}