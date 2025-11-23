<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TUController extends Controller
{
    public function generatePdf($id)
    {
        // Ambil data surat + relasi
        $surat = Surat::with([
            'pengguna',
            'suratDispensasi.detailDispensasi',
            'suratPerintahTugas.detailSpt',
            'persetujuan'
        ])->findOrFail($id);

        // Tentukan jenis surat & template
        if ($surat->suratDispensasi) {
            $template   = TemplateSurat::find(3); // Dispensasi
            $detail     = $surat->suratDispensasi;
            $detailList = $detail->detailDispensasi;
        } elseif ($surat->suratPerintahTugas) {
            $template   = TemplateSurat::find(2); // SPT
            $detail     = $surat->suratPerintahTugas;
            $detailList = $detail->detailSpt;
        } else {
            return back()->with('error', 'Jenis surat tidak dikenali');
        }

        if (!$template) {
            return back()->with('error', 'Template tidak ditemukan');
        }

        // Ambil isi template
        $content = $template->isi_surat;

        // Replace placeholder utama
        $content = str_replace('{{nama_pemohon}}', e($surat->pengguna->nama ?? '-'), $content);
        $content = str_replace('{{nip_pemohon}}', e($surat->pengguna->nip ?? '-'), $content);
        $content = str_replace('{{keperluan}}', e($detail->keperluan ?? '-'), $content);
        $content = str_replace('{{tempat}}', e($detail->tempat ?? '-'), $content);
        $content = str_replace('{{tanggal}}', $detail->tanggal ? Carbon::parse($detail->tanggal)->format('d/m/Y') : '-', $content);
        $content = str_replace('{{hari}}', e($detail->hari ?? '-'), $content);
        $content = str_replace('{{jam}}', e($detail->jam ?? '-'), $content);
        $content = str_replace('{{tanggal_keluar}}', Carbon::now()->format('d/m/Y'), $content);

        // Nomor surat
        $kodeJenis  = $surat->suratDispensasi ? 'SD' : 'SPT';
        $nomorSurat = sprintf(
            "421/%03d/SMKN4-MLG/%s/%s",
            $surat->id_surat,
            $kodeJenis,
            date('Y')
        );
        $content = str_replace('{{nomor_surat}}', $nomorSurat, $content);

        // Tabel
        if ($surat->suratDispensasi) {
            $table = '<table border="1" cellpadding="5" style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NISN</th>
                        <th>Kelas</th>
                    </tr>
                </thead><tbody>';
            $no = 1;

            foreach ($detailList as $row) {
                $table .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . e($row->nama_siswa ?? '-') . '</td>
                        <td>' . e($row->nisn ?? '-') . '</td>
                        <td>' . e($row->kelas ?? '-') . '</td>
                    </tr>';
            }

            $table .= '</tbody></table>';
            $content = str_replace('{{tabel_siswa}}', $table, $content);
        } else {
            $table = '<table border="1" cellpadding="5" style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>Keterangan</th>
                    </tr>
                </thead><tbody>';
            $no = 1;

            foreach ($detailList as $row) {
                $table .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . e($row->nama_guru ?? '-') . '</td>
                        <td>' . e($row->nip ?? '-') . '</td>
                        <td>' . e($row->keterangan ?? '-') . '</td>
                    </tr>';
            }

            $table .= '</tbody></table>';
            $content = str_replace('{{tabel_guru}}', $table, $content);
        }

        // Generate PDF
        $pdf = Pdf::loadHTML($content);

        $filename = 'Surat_' . ($surat->suratDispensasi ? 'Dispensasi' : 'SPT') . '_' .
                    str_replace(' ', '_', $surat->pengguna->nama ?? 'Pemohon') . '_' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
        // Untuk preview inline:
        // return $pdf->stream($filename);
    }
}