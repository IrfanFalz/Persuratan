<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TUController extends Controller
{
    public function generatePdf(Request $request, $id)
    {
        try {
            // Ambil data surat + relasi
            $surat = Surat::with([
            'pengguna',
            'suratDispensasi.detailDispensasi',
            'suratPerintahTugas.detailSpt',
            'persetujuan'
            ])->findOrFail($id);

        // Tentukan jenis surat & ambil template yang digunakan
        $detail = null;
        $detailList = collect();

        // Prioritaskan template yang disimpan di surat (id_template)
        $template = null;
        if ($surat->id_template) {
            $template = TemplateSurat::find($surat->id_template);
        }

        // Jika tidak ada template spesifik pada surat, coba pilih berdasarkan jenis
        if (!$template) {
            if ($surat->suratDispensasi) {
                $template = TemplateSurat::where('tipe', 'dispensasi')->first();
                $detail = $surat->suratDispensasi;
                $detailList = $detail ? $detail->detailDispensasi : collect();
            } elseif ($surat->suratPerintahTugas) {
                $template = TemplateSurat::where('tipe', 'spt')->first();
                $detail = $surat->suratPerintahTugas;
                $detailList = $detail ? $detail->detailSpt : collect();
            }
        } else {
            // Jika ada id_template, set detail berdasarkan tipe surat
            if ($surat->suratDispensasi) {
                $detail = $surat->suratDispensasi;
                $detailList = $detail ? $detail->detailDispensasi : collect();
            } elseif ($surat->suratPerintahTugas) {
                $detail = $surat->suratPerintahTugas;
                $detailList = $detail ? $detail->detailSpt : collect();
            }
        }

        if (!$template) {
            // If client expects JSON, return structured error
            if ($request->wantsJson() || $request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Template tidak ditemukan untuk surat ini.'], 422);
            }

            // If the request asks specifically for a PDF (our frontend uses Accept: application/pdf),
            // return a small valid PDF explaining the missing template so the user still gets a downloadable file.
            $accept = $request->header('Accept') ?? '';
            if (stripos($accept, 'application/pdf') !== false) {
                $fallbackHtml = '<h2>Template Tidak Ditemukan</h2>'
                              .'<p>Template untuk surat ini tidak tersedia. Silakan hubungi administrator atau periksa pengaturan template.</p>'
                              .'<hr>'
                              .'<p><strong>Surat ID:</strong> '.e($surat->id_surat).'</p>'
                              .'<p><strong>Nama Pengaju:</strong> '.e($surat->pengguna->nama ?? '-').'</p>'
                              .'<p><strong>Jenis Surat:</strong> '.($surat->suratDispensasi ? 'Surat Dispensasi' : 'SPT').'</p>';

                $pdf = Pdf::loadHTML($fallbackHtml);
                $filename = 'Surat_Fallback_'.$surat->id_surat.'_'.date('Ymd').'.pdf';
                return $pdf->download($filename);
            }

            // Fallback to redirect with flash for normal browser navigation
            return back()->with('error', 'Template tidak ditemukan');
        }

        // Ambil isi template (field sesuai model: isi_template)
        $content = $template->isi_template;

        // Replace placeholder utama
        // Placeholder umum
        $content = str_replace('{{nama_pemohon}}', e($surat->pengguna->nama ?? '-'), $content);
        $content = str_replace('{{nip_pemohon}}', e($surat->pengguna->nip ?? '-'), $content);
        $content = str_replace('{{keperluan}}', e($detail->keperluan ?? '-'), $content);
        $content = str_replace('{{tempat}}', e($detail->tempat ?? '-'), $content);
        $content = str_replace('{{tanggal}}', $detail && $detail->tanggal ? Carbon::parse($detail->tanggal)->format('d/m/Y') : '-', $content);
        $content = str_replace('{{hari}}', e($detail->hari ?? '-'), $content);
        $content = str_replace('{{jam}}', e($detail->jam ?? '-'), $content);
        // waktu alias jam
        $content = str_replace('{{waktu}}', e($detail->jam ?? $detail->waktu ?? '-'), $content);
        // Kepala sekolah / tanda tangan
        $content = str_replace('{{nama_kepala}}', e(config('app.kepala_nama', 'Kepala Sekolah')), $content);
        $content = str_replace('{{nip_kepala}}', e(config('app.kepala_nip', '')), $content);
        $content = str_replace('{{pangkat_kepala}}', e(config('app.kepala_pangkat', '')), $content);
        $content = str_replace('{{jabatan_kepala}}', e(config('app.kepala_jabatan', '')), $content);
        // acara / kegiatan
        $content = str_replace('{{acara}}', e($detail->acara ?? $detail->keperluan ?? '-'), $content);
        // kota tempat dikeluarkan
        $content = str_replace('{{kota}}', e(config('app.kota', 'Malang')), $content);
        $content = str_replace('{{tanggal_keluar}}', Carbon::now()->format('d/m/Y'), $content);

        // Nomor surat
        $kodeJenis  = $surat->suratDispensasi ? 'SD' : 'SPT';
        $nomorSurat = $surat->nomor_surat;

        $content = str_replace('{{nomor_surat}}', $nomorSurat, $content);

        // Tabel siswa / guru — support beberapa placeholder yang mungkin ada di template
        $tableSiswa = '<table border="1" cellpadding="5" style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NISN / NIP</th>
                        <th>Kelas / Keterangan</th>
                    </tr>
                </thead><tbody>';

        $no = 1;
        foreach ($detailList as $row) {
            // Support both dispensasi detail (nama_siswa, nisn, kelas) and spt detail (nama_guru, nip, keterangan)
            $nama = $row->nama_siswa ?? $row->nama_guru ?? ($surat->pengguna->nama ?? '-');
            $nisn_or_nip = $row->nisn ?? $row->nip ?? ($surat->pengguna->nip ?? '-');
            $kelas_or_ket = $row->kelas ?? $row->keterangan ?? '-';

            $tableSiswa .= '<tr>' .
                "<td>".($no++)."</td>" .
                "<td>".e($nama)."</td>" .
                "<td>".e($nisn_or_nip)."</td>" .
                "<td>".e($kelas_or_ket)."</td>" .
                '</tr>';
        }

        $tableSiswa .= '</tbody></table>';

        // Replace common placeholders
        $content = str_replace(['{{tabel_siswa}}','{{tabel_guru}}','{{guru_table}}','{{table_guru}}'], $tableSiswa, $content);

        // Additional placeholders: nama_terlampir (comma-separated list) and nama_kegiatan
        $terlampirNames = [];
        foreach ($detailList as $row) {
            $n = $row->nama_siswa ?? $row->nama_guru ?? ($surat->pengguna->nama ?? null);
            if ($n) $terlampirNames[] = $n;
        }
        $namaTerlampir = count($terlampirNames) ? implode(', ', $terlampirNames) : '-';
        $namaKegiatan = $detail->acara ?? $detail->keperluan ?? '-';

        $content = str_replace('{{nama_terlampir}}', e($namaTerlampir), $content);
        $content = str_replace('{{nama_kegiatan}}', e($namaKegiatan), $content);

        // Kop surat jika ada - gunakan helper tolerant
        $kopHtml = '';
        if (!empty($template->kop_path)) {
            \Log::debug('TU::generatePdf kop_path found', ['kop_path' => $template->kop_path]);
            $kopHtml = $this->buildKopHtml($template->kop_path);
            // Replace placeholder if present
            if (stripos($content, '{{kop_surat}}') !== false) {
                $content = str_replace('{{kop_surat}}', $kopHtml, $content);
            } else {
                // If template doesn't include placeholder, prepend kop so PDF includes it
                \Log::debug('TU::generatePdf kop placeholder not found, prepending kopHtml');
                $content = $kopHtml . $content;
            }
        } else {
            $content = str_replace('{{kop_surat}}', '', $content);
        }

            // Wrap content into a full HTML document with PDF-friendly CSS so kop and body render together
            $htmlWrap = '<!doctype html><html><head><meta charset="utf-8"><style>'
                . 'body{font-family: DejaVu Sans, sans-serif; font-size:12px; margin:10mm 15mm 15mm 15mm; color:#000; line-height:1.3}'
                . '.kop{ text-align:center; margin-bottom:6px; margin-top:0; } .kop img{ max-width:100%; height:auto; display:block; margin:0 auto; max-height:140px; }'
                . '.content{ margin-top:6px; } table{ border-collapse:collapse; width:100%; } table th, table td{ border:1px solid #333; padding:6px; font-size:11px; }'
                . '.no-break{ page-break-inside:avoid; }'
                . '</style></head><body>' . $content . '</body></html>';

            // Enable remote assets (in case asset URLs are used) and load the wrapped HTML
            Pdf::setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
            $pdf = Pdf::loadHTML($htmlWrap)->setPaper('a4', 'portrait');

            $filename = 'Surat_' . ($surat->suratDispensasi ? 'Dispensasi' : 'SPT') . '_' .
                        str_replace(' ', '_', $surat->pengguna->nama ?? 'Pemohon') . '_' . date('Ymd') . '.pdf';

            return $pdf->download($filename);
            // Untuk preview inline:
            // return $pdf->stream($filename);
        } catch (\Exception $e) {
            // Log full exception for debugging
            Log::error('generatePdf error: ' . $e->getMessage(), ['exception' => $e]);

            // Return structured JSON error so front-end can show useful message
            $message = app()->environment('local') ? $e->getMessage() : 'Gagal menghasilkan PDF. Periksa log server.';
            if ($request->wantsJson() || $request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    // Helper build kop HTML mirip dengan yang di DashboardTuController
    private function buildKopHtml($kopPath, $preview = false)
    {
        if (empty($kopPath)) return '';
        if (stripos($kopPath, '<img') !== false) {
            \Log::debug("TU::buildKopHtml: detected raw img HTML");
            return $kopPath;
        }
        if (preg_match('~^https?://~i', $kopPath)) {
            \Log::debug('TU::buildKopHtml: detected full URL', ['kopPath' => $kopPath]);
            if ($preview) {
                return '<div style="text-align:center;margin-bottom:12px;"><img src="'.e($kopPath).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
            }
            return '<div style="text-align:center;margin-bottom:12px;"><img src="'.e($kopPath).'" style="width:100%;height:auto;"></div>';
        }
        $trim = ltrim($kopPath, '/');
        $candidate = storage_path('app/public/'.$trim);
        \Log::debug('TU::buildKopHtml: checking candidate', ['candidate' => $candidate]);
                if (file_exists($candidate)) {
                    \Log::debug('TU::buildKopHtml: found file at candidate', ['candidate' => $candidate]);
                    if ($preview) {
                        return '<div class="kop" style="text-align:center;margin-bottom:12px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
                    }
                    // For PDF generation, embed the image as base64 so Dompdf includes it reliably
                    try {
                        $bin = file_get_contents($candidate);
                        $mime = function_exists('mime_content_type') ? mime_content_type($candidate) : 'image/png';
                        $b64 = base64_encode($bin);
                        $dataUri = 'data:'.$mime.';base64,'.$b64;
                        return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.e($dataUri).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
                    } catch (\Exception $ex) {
                        \Log::warning('TU::buildKopHtml: failed to embed image, falling back to asset', ['candidate' => $candidate, 'error' => $ex->getMessage()]);
                        return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
                    }
                }
        $candidate2 = storage_path('app/public/templates_image/'.$trim);
        \Log::debug('TU::buildKopHtml: checking candidate2', ['candidate2' => $candidate2]);
        if (file_exists($candidate2)) {
            \Log::debug('TU::buildKopHtml: found file at candidate2', ['candidate2' => $candidate2]);
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
                \Log::warning('TU::buildKopHtml: failed to embed image candidate2, falling back to asset', ['candidate2' => $candidate2, 'error' => $ex->getMessage()]);
                return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.asset('storage/templates_image/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            }
        }
        if (stripos($trim, 'templates_image') !== false) {
            \Log::debug('TU::buildKopHtml: trim contains templates_image, using asset', ['trim' => $trim]);
            if ($preview) {
                return '<div style="text-align:center;margin-bottom:12px;"><img src="'.asset('storage/'.$trim).'" style="max-width:95%;height:auto;max-height:220px;display:block;margin:0 auto;"></div>';
            }
            return '<div style="text-align:center;margin-bottom:12px;"><img src="'.asset('storage/'.$trim).'" style="width:100%;height:auto;"></div>';
        }
        \Log::debug('TU::buildKopHtml: fallback asset', ['trim' => $trim]);
            if ($preview) {
                return '<div class="kop" style="text-align:center;margin-bottom:12px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
            }
        return '<div class="kop" style="text-align:center;margin-bottom:8px;margin-top:0;"><img src="'.asset('storage/'.$trim).'" style="max-width:100%;height:auto;max-height:140px;display:block;margin:0 auto;"></div>';
    }
}