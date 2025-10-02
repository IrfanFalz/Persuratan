<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Persetujuan;
use App\Models\SuratDispensasi;
use App\Models\SuratPerintahTugas;
use App\Models\Pengguna;
use App\Helpers\NotifikasiHelper;

class SuratController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'jenis'     => 'required|in:dispensasi,spt',
            'keperluan' => 'required'
        ]);

        $surat = Surat::create([
            'id_pengguna'   => auth()->id(),
            'status_berkas' => 'diajukan'
        ]);

        $reviewer = Pengguna::whereIn('role', ['ktu', 'kepsek'])->first();

        $pers = Persetujuan::create([
            'id_surat'    => $surat->id_surat,
            'id_pengguna' => $reviewer->id_pengguna,
            'disetujui'   => null
        ]);

        if ($req->jenis == 'dispensasi') {
            SuratDispensasi::create([
                'id_surat'      => $surat->id_surat,
                'id_persetujuan'=> $pers->id_persetujuan,
                'keperluan'     => $req->keperluan,
                'tempat'        => $req->tempat,
                'tanggal'       => $req->tanggal,
                'jam'           => $req->jam,
                'hari'          => $req->hari
            ]);
        } elseif ($req->jenis == 'spt') {
            SuratPerintahTugas::create([
                'id_surat'      => $surat->id_surat,
                'id_persetujuan'=> $pers->id_persetujuan,
                'keperluan'     => $req->keperluan,
                'tempat'        => $req->tempat,
                'tanggal'       => $req->tanggal,
                'jam'           => $req->jam,
                'hari'          => $req->hari
            ]);
        }

        NotifikasiHelper::insert(
            $surat->id_surat,
            $reviewer->id_pengguna,
            'Ada surat baru menunggu persetujuan',
            null
        );

        return redirect()->route('form.surat', ['type' => $req->jenis])
            ->with('success_message', 'Surat berhasil diajukan');
    }

    public function index()
    {
        $surat = Surat::latest('id_surat')->take(5)->get();
        return view('surat.index', compact('surat'));
    }
}
