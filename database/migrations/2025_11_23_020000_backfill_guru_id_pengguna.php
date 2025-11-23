<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill id_pengguna in guru table by matching guru.nip to pengguna.nip
        if (! Schema::hasTable('guru') || ! Schema::hasTable('pengguna')) {
            return;
        }

        $penggunas = DB::table('pengguna')->where('role', 'GURU')->get(['id_pengguna','nip']);

        foreach ($penggunas as $p) {
            DB::table('guru')
                ->where('nip', $p->nip)
                ->whereNull('id_pengguna')
                ->update(['id_pengguna' => $p->id_pengguna]);
        }
    }

    public function down(): void
    {
        // Reverse: clear id_pengguna for guru rows that match a pengguna with same nip
        if (! Schema::hasTable('guru') || ! Schema::hasTable('pengguna')) {
            return;
        }

        $penggunas = DB::table('pengguna')->where('role', 'GURU')->get(['id_pengguna','nip']);
        foreach ($penggunas as $p) {
            DB::table('guru')
                ->where('nip', $p->nip)
                ->where('id_pengguna', $p->id_pengguna)
                ->update(['id_pengguna' => null]);
        }
    }
};
