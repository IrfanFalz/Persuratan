<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill id_jenis_surat based on existence in child tables
        $surats = DB::table('surat')->get(['id_surat', 'id_jenis_surat']);
        foreach ($surats as $s) {
            if (!is_null($s->id_jenis_surat)) continue;

            $hasDisp = DB::table('surat_dispensasi')->where('id_surat', $s->id_surat)->exists();
            $hasSpt  = DB::table('surat_perintah_tugas')->where('id_surat', $s->id_surat)->exists();

            if ($hasDisp) {
                DB::table('surat')->where('id_surat', $s->id_surat)->update(['id_jenis_surat' => 1]);
            } elseif ($hasSpt) {
                DB::table('surat')->where('id_surat', $s->id_surat)->update(['id_jenis_surat' => 2]);
            }
        }

        // Resolve regional IDs from lookup tables using codes in config
        $kodeProv = Config::get('nomor_surat.provinsi_code');
        $kodeDinas = Config::get('nomor_surat.dinas_code');
        $kodeSek = Config::get('nomor_surat.sekolah_code');

        $provId = DB::table('provinsi_surat')->where('kode_provinsi', $kodeProv)->value('id');
        $dinasId = DB::table('dinas_surat')->where('kode_dinas', $kodeDinas)->value('id');
        $sekolahId = DB::table('sekolah_surat')->where('kode_sekolah', $kodeSek)->value('id');

        if ($provId) {
            DB::table('surat')->whereNull('id_provinsi')->update(['id_provinsi' => $provId]);
        }
        if ($dinasId) {
            DB::table('surat')->whereNull('id_dinas')->update(['id_dinas' => $dinasId]);
        }
        if ($sekolahId) {
            DB::table('surat')->whereNull('id_sekolah')->update(['id_sekolah' => $sekolahId]);
        }
    }

    public function down(): void
    {
        // Do not revert automatic backfill
    }
};
