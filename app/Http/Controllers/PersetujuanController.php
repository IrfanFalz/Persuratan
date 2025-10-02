<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persetujuan;
use App\Helpers\NotifikasiHelper;

class PersetujuanController extends Controller
{
    public function update(Request $req, $id)
    {
        $req->validate([
            'disetujui' => 'required|in:ya,tidak',
        ]);

        $pers = Persetujuan::findOrFail($id);

        $pers->disetujui = $req->disetujui;
        $pers->catatan = $req->catatan ?? null;
        $pers->timestamp = now();
        $pers->save();

        $surat = $pers->surat;
        if ($surat) {
            $surat->status_berkas = $req->disetujui === 'ya' ? 'disetujui' : 'ditolak';
            $surat->save();
        }

        NotifikasiHelper::insert(
            $surat->id_surat ?? null,
            $surat->id_pengguna ?? null,
            $req->disetujui === 'ya'
                ? 'Suratmu telah disetujui oleh Kepala Sekolah'
                : 'Suratmu ditolak oleh Kepala Sekolah',
            $req->disetujui === 'ya' ? 'disetujui' : 'ditolak'
        );

        return back()->with('success_message', 'Persetujuan berhasil disimpan');
    }
}
