<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Helpers\NotifikasiHelper;
use PDF;
use Storage;

class TUController extends Controller
{
    public function generatePdf($id_surat)
    {
        $surat = Surat::with('dispensasi','spt','pengguna')->findOrFail($id_surat);

        if($surat->dispensasi){
            $html = view('pdf.surat_dispensasi', compact('surat'))->render();
        } else {
            $html = view('pdf.surat_spt', compact('surat'))->render();
        }

        $pdf = PDF::loadHTML($html)->setPaper('a4','portrait');

        $filename = 'surat_'.$surat->id_surat.'_'.time().'.pdf';
        Storage::put('public/surat/'.$filename, $pdf->output());

        if($surat->dispensasi){
            $surat->dispensasi->lampiran = 'storage/surat/'.$filename;
            $surat->dispensasi->save();
        } elseif($surat->spt){
            $surat->spt->lampiran = 'storage/surat/'.$filename;
            $surat->spt->save();
        }

        $surat->status_berkas = 'diproses';
        $surat->save();

        NotifikasiHelper::insert(
            $surat->id_surat,
            $surat->id_pengguna,
            'Surat telah dibuat oleh TU. Silakan diambil.',
            'selesai'
        );

        return response()->json([
            'message' => 'PDF dibuat',
            'path'    => 'storage/surat/'.$filename
        ]);
    }
}
