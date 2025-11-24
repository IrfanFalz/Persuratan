<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateSurat;

class TemplateSuratSeeder extends Seeder
{
    public function run()
    {
        // Jika sudah ada template dispensasi, jangan duplikasi
        $exists = TemplateSurat::where('tipe', 'dispensasi')->first();
        if ($exists) return;

        $html = <<<'HTML'
<div style="font-family: Arial, Helvetica, sans-serif; color:#000;">
    {{kop_surat}}

    <h2 style="text-align:center; letter-spacing:4px; margin:6px 0;">SURAT DISPENSASI</h2>
    <p style="text-align:center; margin:4px 0; font-weight:600;">Nomor: {{nomor_surat}}</p>

    <p>Yang bertanda tangan di bawah ini :</p>
    <table style="width:100%; margin-left:20px;">
        <tr><td style="width:120px;">Nama</td><td> : {{nama_kepala}}</td></tr>
        <tr><td>NIP</td><td> : {{nip_kepala}}</td></tr>
        <tr><td>Pangkat/Golongan</td><td> : {{pangkat_kepala}}</td></tr>
        <tr><td>Jabatan</td><td> : {{jabatan_kepala}}</td></tr>
    </table>

    <p style="margin-top:12px;">Memberikan dispensasi kepada : <b>Nama-nama Terlampir</b></p>

    <p>Untuk tidak mengikuti pembelajaran di kelas, dikarenakan menjadi peserta <b>{{acara}}</b> yang akan dilaksanakan pada :</p>

    <table style="margin-left:20px;">
        <tr><td style="width:120px;">hari</td><td> : {{hari}}</td></tr>
        <tr><td>tanggal</td><td> : {{tanggal}}</td></tr>
        <tr><td>waktu</td><td> : {{waktu}}</td></tr>
        <tr><td>tempat</td><td> : {{tempat}}</td></tr>
    </table>

    <p>Demikian surat dispensasi ini dibuat, untuk dipergunakan sebagaimana mestinya.</p>

    {{tabel_siswa}}

    <div style="width:100%; display:flex; justify-content:flex-end; margin-top:24px;">
        <div style="text-align:left; width:300px;">
            <p>Dikeluarkan di : {{kota}}</p>
            <p>Pada Tanggal : {{tanggal_keluar}}</p>
            <p style="margin-top:36px;"><b>{{nama_kepala}}</b><br>{{pangkat_kepala}}<br>NIP. {{nip_kepala}}</p>
        </div>
    </div>
</div>
HTML;

        TemplateSurat::create([
            'nama' => 'Surat Dispensasi - Default',
            'deskripsi' => 'Template default surat dispensasi (format resmi sekolah).',
            'tipe' => 'dispensasi',
            'kop_path' => null,
            'isi_template' => $html,
        ]);
    }
}
