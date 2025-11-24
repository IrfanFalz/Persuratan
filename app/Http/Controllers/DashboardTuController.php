<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\SuratDispensasi;
use App\Models\SuratPerintahTugas;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\Persetujuan;
use App\Helpers\NotifikasiHelper;
use App\Helpers\NomorSuratHelper;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\SuratStatusUpdated;
use Carbon\Carbon;

class DashboardTuController extends Controller
{
    public function index()
    {
        // Ambil surat yang status-nya dianggap 'approved'
        $approved_letters = Surat::with(['pengguna', 'template', 'suratDispensasi', 'suratPerintahTugas.detail'])
            ->whereIn('status_berkas', ['approved', 'disetujui', 'ya', 'approve', 'diproses'])
            ->orderBy('dibuat_pada', 'desc')
            ->get()
            ->map(function ($surat) {
                $detail = $surat->suratDispensasi ?? ($surat->suratPerintahTugas ?? null);

                // Jika tidak ada detail, skip (log untuk debugging)
                if (!$detail) {
                    Log::warning("Surat ID {$surat->id_surat} tidak memiliki detail");
                    return null;
                }

                $rendered = null;

                // Jika surat tidak memiliki template yang ter-link, coba fallback berdasarkan tipe surat
                $tmpl = null;
                if ($surat->template && !empty($surat->template->isi_template)) {
                    $tmpl = $surat->template->isi_template;
                } else {
                    // Fallback: pilih template berdasarkan tipe (dispensasi / spt)
                    if ($surat->suratDispensasi) {
                        $fallback = \App\Models\TemplateSurat::where('tipe', 'dispensasi')->first();
                        if ($fallback) $tmpl = $fallback->isi_template;
                    } elseif ($surat->suratPerintahTugas) {
                        $fallback = \App\Models\TemplateSurat::where('tipe', 'spt')->first();
                        if ($fallback) $tmpl = $fallback->isi_template;
                    }
                }

                if ($tmpl) {

                    // Buat tabel guru / pemohon
                    $guruRows = '';
                    $guruList = [];
                    if ($surat->suratPerintahTugas) {
                        $details = $surat->suratPerintahTugas->detail ?? collect();
                        foreach ($details as $idx => $d) {
                            $guruRows .= '<tr><td class="py-1">'.($idx+1).'</td>'
                                      .'<td class="py-1">'.e($d->nama_guru ?? '-').'</td>'
                                      .'<td class="py-1">'.e($d->nip ?? '-').'</td>'
                                      .'<td class="py-1">'.e($d->keterangan ?? '-').'</td></tr>';
                            $guruList[] = $d;
                        }
                    }

                    if (empty($guruRows) && $surat->suratDispensasi) {
                        // untuk dispensasi tampilkan pemohon sebagai satu baris
                        $pemohonNama = $surat->pengguna->nama ?? '-';
                        $pemohonNip = $surat->pengguna->nip ?? '-';
                        $guruRows = '<tr><td class="py-1">1</td>'
                                  .'<td class="py-1">'.e($pemohonNama).'</td>'
                                  .'<td class="py-1">'.e($pemohonNip).'</td>'
                                  .'<td class="py-1">Yang mengajukan</td></tr>';
                    }

                    $guruTable = '<table class="w-full border-collapse" style="border:1px solid #ddd">'
                                .'<thead><tr><th class="py-1">No.</th><th class="py-1">Nama</th><th class="py-1">NIP</th><th class="py-1">Keterangan</th></tr></thead>'
                                .'<tbody>'.$guruRows.'</tbody></table>';

                    // kop surat jika tersedia
                    $kopHtml = '';
                    if ($surat->template && $surat->template->kop_path) {
                        // for preview in modal, render a scaled kop so it fits the modal
                        $kopHtml = $this->buildKopHtml($surat->template->kop_path, true);
                    }

                    // Build nama_terlampir (joined names) and nama_kegiatan
                    $namaTerlampir = '';
                    if ($surat->suratPerintahTugas) {
                        $names = [];
                        $details = $surat->suratPerintahTugas->detail ?? collect();
                        foreach ($details as $d) {
                            if (!empty($d->nama_guru)) $names[] = $d->nama_guru;
                        }
                        $namaTerlampir = implode(', ', $names);
                    } elseif ($surat->suratDispensasi) {
                        $names = [];
                        $details = $surat->suratDispensasi->detail ?? collect();
                        foreach ($details as $d) {
                            if (!empty($d->nama_siswa)) $names[] = $d->nama_siswa;
                        }
                        $namaTerlampir = implode(', ', $names);
                    }

                    $namaKegiatan = $detail->acara ?? $detail->keperluan ?? '-';

                    $replacements = [
                        '{{nomor}}' => $surat->nomor_surat ?? $this->formatNomorSurat($surat),
                        '{{nomor_surat}}' => $surat->nomor_surat ?? $this->formatNomorSurat($surat),
                        '{{nama_pemohon}}' => $surat->pengguna->nama ?? '-',
                        '{{nip_pemohon}}' => $surat->pengguna->nip ?? '-',
                        '{{nama_terlampir}}' => $namaTerlampir ?: '-',
                        '{{nama_kegiatan}}' => $namaKegiatan ?: '-',
                        '{{hari}}' => $detail->hari ?? '-',
                        '{{dasar}}' => $detail->dasar ?? ($surat->keterangan ?? '-'),
                        '{{keperluan}}' => $detail->keperluan ?? '-',
                        '{{acara}}' => $detail->acara ?? $detail->keperluan ?? '-',
                        '{{tanggal_tugas}}' => $detail->tanggal ?? '-',
                        '{{tanggal}}' => $detail->tanggal ?? '-',
                        '{{tempat_tugas}}' => $detail->tempat ?? '-',
                        '{{tempat}}' => $detail->tempat ?? '-',
                        '{{waktu_tugas}}' => $detail->jam ?? '-',
                        '{{waktu}}' => $detail->jam ?? '-',
                        '{{jam}}' => $detail->jam ?? '-',
                        '{{tanggal_surat}}' => Carbon::now()->format('d F Y'),
                        '{{tanggal_keluar}}' => Carbon::now()->format('d/m/Y'),
                        '{{nama_kepala}}' => config('app.kepala_nama', 'Kepala Sekolah'),
                        '{{nip_kepala}}' => config('app.kepala_nip', ''),
                        '{{pangkat_kepala}}' => config('app.kepala_pangkat', ''),
                        '{{jabatan_kepala}}' => config('app.kepala_jabatan', ''),
                        '{{nama_kepala_sekolah}}' => config('app.kepala_nama', 'Kepala Sekolah'),
                        '{{nip_kepala_sekolah}}' => config('app.kepala_nip', ''),
                        '{{guru_table}}' => $guruTable,
                        '{{tabel_siswa}}' => $guruTable,
                        '{{kop_surat}}' => $kopHtml,
                        '{{kota}}' => config('app.kota', 'Malang'),
                    ];

                    // Lakukan penggantian placeholder
                    $rendered = strtr($tmpl, $replacements);

                    // Jika template tidak menyertakan placeholder {{kop_surat}},
                    // tambahkan kopHtml di bagian atas agar preview sama dengan admin preview
                    if (!empty($kopHtml) && stripos($tmpl, '{{kop_surat}}') === false) {
                        $rendered = $kopHtml . $rendered;
                    }
                } else {
                    Log::warning('No template available for Surat ID '.$surat->id_surat);
                }
                

                return [
                    'id' => $surat->id_surat,
                    'teacher' => $surat->pengguna->nama ?? '-',
                    'nip' => $surat->pengguna->nip ?? '-',
                    'phone' => $surat->pengguna->no_telp ?? '-',
                    'full_name' => $surat->pengguna->nama ?? '-',
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
            ->filter() // hapus null
            ->values();

        // SURAT YANG SUDAH SELESAI (status: completed)
        $completed_letters = Surat::with(['pengguna', 'template', 'suratDispensasi', 'suratPerintahTugas.detail'])
            ->whereIn('status_berkas', ['completed', 'done', 'selesai'])
            ->orderBy('dibuat_pada', 'desc')
            ->get()
            ->map(function ($surat) {
                $detail = $surat->suratDispensasi ?? ($surat->suratPerintahTugas ?? null);

                if (!$detail) return null;

                $rendered = null;
                if ($surat->template && !empty($surat->template->isi_template)) {
                    $tmpl = $surat->template->isi_template;

                    $guruRows = '';
                    if ($surat->suratPerintahTugas) {
                        $details = $surat->suratPerintahTugas->detail ?? collect();
                        foreach ($details as $idx => $d) {
                            $guruRows .= '<tr><td class="py-1">'.($idx+1).'</td>'
                                      .'<td class="py-1">'.e($d->nama_guru ?? '-').'</td>'
                                      .'<td class="py-1">'.e($d->nip ?? '-').'</td>'
                                      .'<td class="py-1">'.e($d->keterangan ?? '-').'</td></tr>';
                        }
                    }

                    if (empty($guruRows) && $surat->suratDispensasi) {
                        $pemohonNama = $surat->pengguna->nama ?? '-';
                        $pemohonNip = $surat->pengguna->nip ?? '-';
                        $guruRows = '<tr><td class="py-1">1</td>'
                                  .'<td class="py-1">'.e($pemohonNama).'</td>'
                                  .'<td class="py-1">'.e($pemohonNip).'</td>'
                                  .'<td class="py-1">Yang mengajukan</td></tr>';
                    }

                    $guruTable = '<table class="w-full border-collapse" style="border:1px solid #ddd">'
                                .'<thead><tr><th class="py-1">No.</th><th class="py-1">Nama</th><th class="py-1">NIP</th><th class="py-1">Keterangan</th></tr></thead>'
                                .'<tbody>'.$guruRows.'</tbody></table>';

                    $kopHtml = '';
                    if ($surat->template && $surat->template->kop_path) {
                        $kopHtml = $this->buildKopHtml($surat->template->kop_path, true);
                    }

                    // Build nama_terlampir (joined names) and nama_kegiatan
                    $namaTerlampir = '';
                    if ($surat->suratPerintahTugas) {
                        $names = [];
                        $details = $surat->suratPerintahTugas->detail ?? collect();
                        foreach ($details as $d) {
                            if (!empty($d->nama_guru)) $names[] = $d->nama_guru;
                        }
                        $namaTerlampir = implode(', ', $names);
                    } elseif ($surat->suratDispensasi) {
                        $names = [];
                        $details = $surat->suratDispensasi->detail ?? collect();
                        foreach ($details as $d) {
                            if (!empty($d->nama_siswa)) $names[] = $d->nama_siswa;
                        }
                        $namaTerlampir = implode(', ', $names);
                    }

                    $namaKegiatan = $detail->acara ?? $detail->keperluan ?? '-';

                    $replacements = [
                        '{{nomor}}' => $surat->nomor_surat ?? $this->formatNomorSurat($surat),
                        '{{nomor_surat}}' => $surat->nomor_surat ?? $this->formatNomorSurat($surat),
                        '{{nama_pemohon}}' => $surat->pengguna->nama ?? '-',
                        '{{nip_pemohon}}' => $surat->pengguna->nip ?? '-',
                        '{{nama_terlampir}}' => $namaTerlampir ?: '-',
                        '{{nama_kegiatan}}' => $namaKegiatan ?: '-',
                        '{{hari}}' => $detail->hari ?? '-',
                        '{{dasar}}' => $detail->dasar ?? ($surat->keterangan ?? '-'),
                        '{{keperluan}}' => $detail->keperluan ?? '-',
                        '{{acara}}' => $detail->acara ?? $detail->keperluan ?? '-',
                        '{{tanggal_tugas}}' => $detail->tanggal ?? '-',
                        '{{tanggal}}' => $detail->tanggal ?? '-',
                        '{{tempat_tugas}}' => $detail->tempat ?? '-',
                        '{{tempat}}' => $detail->tempat ?? '-',
                        '{{waktu_tugas}}' => $detail->jam ?? '-',
                        '{{waktu}}' => $detail->jam ?? '-',
                        '{{jam}}' => $detail->jam ?? '-',
                        '{{tanggal_surat}}' => Carbon::now()->format('d F Y'),
                        '{{tanggal_keluar}}' => Carbon::now()->format('d/m/Y'),
                        '{{nama_kepala}}' => config('app.kepala_nama', 'Kepala Sekolah'),
                        '{{nip_kepala}}' => config('app.kepala_nip', ''),
                        '{{pangkat_kepala}}' => config('app.kepala_pangkat', ''),
                        '{{jabatan_kepala}}' => config('app.kepala_jabatan', ''),
                        '{{nama_kepala_sekolah}}' => config('app.kepala_nama', 'Kepala Sekolah'),
                        '{{nip_kepala_sekolah}}' => config('app.kepala_nip', ''),
                        '{{guru_table}}' => $guruTable,
                        '{{tabel_siswa}}' => $guruTable,
                        '{{kop_surat}}' => $kopHtml,
                        '{{kota}}' => config('app.kota', 'Malang'),
                    ];

                    $rendered = strtr($tmpl, $replacements);
                }

                return [
                    'id' => $surat->id_surat,
                    'teacher' => $surat->pengguna->nama ?? '-',
                    'nip' => $surat->pengguna->nip ?? '-',
                    'phone' => $surat->pengguna->no_telp ?? '-',
                    'full_name' => $surat->pengguna->nama ?? '-',
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

        // Indexing arrays untuk akses cepat
        $letters_indexed = $approved_letters->keyBy('id')->toArray();
        $completed_letters_indexed = $completed_letters->keyBy('id')->toArray();

        // Pending approval count (sederhana — sesuaikan status sesuai skema Anda)
        $pending_approval = Surat::whereIn('status_berkas', ['pending', 'baru', 'submitted', 'menunggu'])
            ->orderBy('dibuat_pada', 'desc')
            ->take(10)
            ->get();

        // Monthly stats sederhana: jumlah per status di bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $monthly_stats = Surat::selectRaw('status_berkas, count(*) as total')
            ->where('dibuat_pada', '>=', $startOfMonth)
            ->groupBy('status_berkas')
            ->pluck('total', 'status_berkas')
            ->toArray();

        // Aktivitas terbaru: gabungkan persetujuan dengan surat yang baru selesai
        $approvalActivities = Persetujuan::with('surat','pengguna')
            ->whereNotNull('disetujui')
            ->orderBy('timestamp', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $surat = $item->surat;
                $days = 0;
                if ($surat && $surat->dibuat_pada) {
                    $days = Carbon::parse($surat->dibuat_pada)->diffInDays(Carbon::now());
                }

                $reason = $surat->suratDispensasi->keperluan
                            ?? ($surat->suratPerintahTugas->keperluan ?? '-');

                return [
                    'id'       => $item->id_persetujuan,
                    'teacher'  => $surat->pengguna->nama ?? 'Guru tidak diketahui',
                    'nip'      => $surat->pengguna->nip ?? '-',
                    'no_telp'  => $surat->pengguna->no_telp ?? '-',
                    'type'     => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'reason'   => $reason,
                    'date'     => $item->timestamp,
                    'status'   => $item->disetujui,
                    'duration' => $days === 0 ? 'Baru diajukan' : $days . ' hari',
                    'surat_id' => $surat->id_surat ?? null,
                ];
            })->toArray();

        // Surat yang selesai baru-baru ini
        $completedActivities = Surat::with(['pengguna','suratDispensasi','suratPerintahTugas'])
            ->whereIn('status_berkas', ['completed','done','selesai'])
            ->orderBy('dibuat_pada', 'desc')
            ->take(10)
            ->get()
            ->map(function ($surat) {
                $reason = $surat->suratDispensasi->keperluan ?? ($surat->suratPerintahTugas->keperluan ?? '-');
                return [
                    'id' => 's_'.$surat->id_surat,
                    'teacher' => $surat->pengguna->nama ?? 'Guru tidak diketahui',
                    'nip' => $surat->pengguna->nip ?? '-',
                    'no_telp' => $surat->pengguna->no_telp ?? '-',
                    'type' => $surat->suratDispensasi ? 'Surat Dispensasi' : 'Surat Perintah Tugas',
                    'reason' => $reason,
                    'date' => $surat->dibuat_pada,
                    'status' => 'completed',
                    'duration' => Carbon::parse($surat->dibuat_pada)->diffInDays(Carbon::now()) . ' hari',
                    'surat_id' => $surat->id_surat,
                ];
            })->toArray();

        // Gabungkan dan urutkan berdasarkan tanggal terbaru, ambil 5 teratas
        $merged = collect(array_merge($approvalActivities, $completedActivities))
            ->sortByDesc(function ($a) {
                return isset($a['date']) ? strtotime($a['date']) : 0;
            })->values()->take(5)->toArray();

        $recent_activities = collect($merged);

        $template_spt = TemplateSurat::where('tipe', 'spt')->first();
        $template_dispensasi = TemplateSurat::where('tipe', 'dispensasi')->first();

        return view('dashboard-tu', compact(
            'approved_letters',
            'completed_letters',
            'letters_indexed',
            'completed_letters_indexed',
            'pending_approval',
            'monthly_stats',
            'recent_activities'
        , 'template_spt', 'template_dispensasi'));
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
                'nama' => $surat->pengguna->nama ?? '-',
                'nip' => $surat->pengguna->nip ?? '-',
                'keterangan' => 'Yang mengajukan',
            ];
        }

        return $list;
    }

    // Helper build kop HTML yang toleran terhadap beberapa format kop_path
    private function buildKopHtml($kopPath, $preview = false)
    {
        if (empty($kopPath)) return '';

        // Jika sudah berisi tag <img>, kembalikan apa adanya
        if (stripos($kopPath, '<img') !== false) {
            Log::debug("buildKopHtml: detected raw img HTML");
            return $kopPath;
        }

        // Jika full URL, gunakan langsung
        if (preg_match('~^https?://~i', $kopPath)) {
            Log::debug('buildKopHtml: detected full URL', ['kopPath' => $kopPath]);
            if ($preview) {
                return '<div style="text-align:center;margin-bottom:12px;"><img src="'.e($kopPath).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
            }
            return '<div style="text-align:center;margin-bottom:12px;"><img src="'.e($kopPath).'" style="width:100%;height:auto;"></div>';
        }

        // Normalisasi path
        $trim = ltrim($kopPath, '/');

        // Cek file di storage/app/public/{path}
        $candidate = storage_path('app/public/'.$trim);
        Log::debug('buildKopHtml: checking candidate', ['candidate' => $candidate]);
        if (file_exists($candidate)) {
            Log::debug('buildKopHtml: found file at candidate', ['candidate' => $candidate]);
            if ($preview) {
                return '<div class="kop" style="text-align:center;margin-bottom:12px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
            }
            // For server-side rendering / PDF generation it is more reliable to embed image as base64
            try {
                $bin = file_get_contents($candidate);
                $mime = function_exists('mime_content_type') ? mime_content_type($candidate) : 'image/png';
                $b64 = base64_encode($bin);
                $dataUri = 'data:'.$mime.';base64,'.$b64;
                return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.e($dataUri).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            } catch (\Exception $ex) {
                Log::warning('buildKopHtml: failed to embed image, falling back to asset', ['candidate' => $candidate, 'error' => $ex->getMessage()]);
                return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            }
        }

        // Cek alternatif di storage/app/public/templates_image/{path}
        $candidate2 = storage_path('app/public/templates_image/'.$trim);
        Log::debug('buildKopHtml: checking candidate2', ['candidate2' => $candidate2]);
        if (file_exists($candidate2)) {
            Log::debug('buildKopHtml: found file at candidate2', ['candidate2' => $candidate2]);
            if ($preview) {
                return '<div class="kop" style="text-align:center;margin-bottom:12px;margin-top:0;"><img src="'.asset('storage/templates_image/'.$trim).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
            }
            try {
                $bin = file_get_contents($candidate2);
                $mime = function_exists('mime_content_type') ? mime_content_type($candidate2) : 'image/png';
                $b64 = base64_encode($bin);
                $dataUri = 'data:'.$mime.';base64,'.$b64;
                return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.e($dataUri).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            } catch (\Exception $ex) {
                Log::warning('buildKopHtml: failed to embed image at candidate2, falling back to asset', ['candidate2' => $candidate2, 'error' => $ex->getMessage()]);
                return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.asset('storage/templates_image/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            }
        }

        // Jika string mengandung folder templates_image, coba gunakan asset langsung
        if (stripos($trim, 'templates_image') !== false) {
            Log::debug('buildKopHtml: trim contains templates_image, using asset', ['trim' => $trim]);
            if ($preview) {
                return '<div style="text-align:center;margin-bottom:12px;"><img src="'.asset('storage/'.$trim).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
            }
            return '<div style="text-align:center;margin-bottom:12px;"><img src="'.asset('storage/'.$trim).'" style="width:100%;height:auto;"></div>';
        }

        Log::debug('buildKopHtml: fallback asset', ['trim' => $trim]);
        // Fallback: coba gunakan asset('storage/{trim}') meskipun file tidak ditemukan
            if ($preview) {
                return '<div class="kop" style="text-align:center;margin-bottom:12px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            }
        return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
    }

    // Format nomor surat default
    private function formatNomorSurat($surat)
    {
        try {
            $kodeJenis = $surat->suratDispensasi ? 'SD' : 'SPT';
            $idNumber = $surat->id_surat ?? 0;
            $nomorSurat = sprintf(
                "421/%03d/SMKN4-MLG/%s/%s",
                $idNumber,
                $kodeJenis,
                date('Y')
            );
            return $nomorSurat;
        } catch (\Exception $e) {
            Log::error('formatNomorSurat error: '.$e->getMessage());
            return $surat->nomor_surat ?? '—';
        }
    }

    // Proses surat (selesaikan surat)
    public function process(Request $request)
    {
        $action = $request->input('action');
        $letterId = $request->input('letter_id');

        $surat = Surat::with('pengguna')->findOrFail($letterId);
        $teacher = $surat->pengguna->nama ?? 'Pengguna';

        if ($action === 'complete') {
            // First, attempt to generate nomor surat (nomor_surat & nomor_urut).
            $generated = null;
            try {
                if (class_exists(NomorSuratHelper::class)) {
                    $generated = NomorSuratHelper::generate($surat);
                }
            } catch (\Exception $e) {
                Log::error('Failed to generate nomor surat: '.$e->getMessage());
            }

            if (empty($generated)) {
                // If nomor generation failed, do NOT mark as done. Inform TU to retry.
                $message = "Gagal menghasilkan nomor surat. Status tidak diubah. Periksa log server.";
                return redirect()->route('dashboard.tu')->with('error', $message);
            }

            // Update status jadi selesai/done (gunakan 'done' supaya konsisten dengan UI guru)
            $surat->update(['status_berkas' => 'done']);

            // Broadcast update supaya dashboard guru menerima perubahan realtime
            try {
                broadcast(new SuratStatusUpdated($surat))->toOthers();
            } catch (\Exception $e) {
                Log::error('Failed to broadcast SuratStatusUpdated: '.$e->getMessage());
            }

            // Kirim notifikasi otomatis ke guru
            try {
                NotifikasiHelper::insert(
                    $surat->id_surat,
                    $surat->id_pengguna,
                    "Surat Anda sudah selesai dan siap diambil di TU",
                    null
                );
            } catch (\Exception $e) {
                Log::error('Failed to send notification: '.$e->getMessage());
            }

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
        $teacher = $surat->pengguna->nama ?? 'Pengguna';

        try {
            NotifikasiHelper::insert(
                $surat->id_surat,
                $surat->id_pengguna,
                "Reminder: Surat Anda sudah selesai dan menunggu pengambilan di TU",
                null
            );

            $message = "Notifikasi berhasil dikirim ulang ke {$teacher}.";
        } catch (\Exception $e) {
            Log::error('resendNotification failed: '.$e->getMessage());
            $message = "Gagal mengirim notifikasi ulang: " . $e->getMessage();
        }

        return redirect()->route('dashboard.tu')->with('message', $message);
    }

    // Get detail surat (untuk modal/preview)
    public function getLetterDetail($id)
    {
        $surat = Surat::with(['pengguna', 'suratDispensasi', 'suratPerintahTugas.detail'])
            ->findOrFail($id);

        $detail = $surat->suratDispensasi ?? ($surat->suratPerintahTugas ?? null);

        $data = [
            'id' => $surat->id_surat,
            'teacher' => $surat->pengguna->nama ?? '-',
            'nip' => $surat->pengguna->nip ?? '-',
            'phone' => $surat->pengguna->no_telp ?? '-',
            'full_name' => $surat->pengguna->nama ?? '-',
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
