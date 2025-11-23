<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            if (!Schema::hasColumn('surat', 'id_jenis_surat')) {
                $table->unsignedBigInteger('id_jenis_surat')->nullable()->after('id_persetujuan');
            }
            if (!Schema::hasColumn('surat', 'id_provinsi')) {
                $table->unsignedBigInteger('id_provinsi')->nullable()->after('id_jenis_surat');
            }
            if (!Schema::hasColumn('surat', 'id_dinas')) {
                $table->unsignedBigInteger('id_dinas')->nullable()->after('id_provinsi');
            }
            if (!Schema::hasColumn('surat', 'id_sekolah')) {
                $table->unsignedBigInteger('id_sekolah')->nullable()->after('id_dinas');
            }
            if (!Schema::hasColumn('surat', 'nomor_urut')) {
                $table->integer('nomor_urut')->nullable()->after('id_sekolah');
            }
            if (!Schema::hasColumn('surat', 'nomor_surat')) {
                $table->string('nomor_surat')->nullable()->after('nomor_urut');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn([
                'id_jenis_surat',
                'id_provinsi',
                'id_dinas',
                'id_sekolah',
                'nomor_urut',
                'nomor_surat'
            ]);
        });
    }
};
