<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_dispensasi', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_dispensasi', 'keperluan')) {
                $table->string('keperluan')->nullable();
            }
            if (!Schema::hasColumn('detail_dispensasi', 'hari')) {
                $table->string('hari')->nullable();
            }
            if (!Schema::hasColumn('detail_dispensasi', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }
            if (!Schema::hasColumn('detail_dispensasi', 'jam')) {
                $table->string('jam')->nullable();
            }
            if (!Schema::hasColumn('detail_dispensasi', 'tempat')) {
                $table->string('tempat')->nullable();
            }
        });

        Schema::table('detai_spt', function (Blueprint $table) {
            if (!Schema::hasColumn('detai_spt', 'keperluan')) {
                $table->string('keperluan')->nullable();
            }
            if (!Schema::hasColumn('detai_spt', 'hari')) {
                $table->string('hari')->nullable();
            }
            if (!Schema::hasColumn('detai_spt', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }
            if (!Schema::hasColumn('detai_spt', 'jam')) {
                $table->string('jam')->nullable();
            }
            if (!Schema::hasColumn('detai_spt', 'tempat')) {
                $table->string('tempat')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('detail_dispensasi', function (Blueprint $table) {
            $table->dropColumn(['keperluan', 'hari', 'tanggal', 'jam', 'tempat']);
        });

        Schema::table('detai_spt', function (Blueprint $table) {
            $table->dropColumn(['keperluan', 'hari', 'tanggal', 'jam', 'tempat']);
        });
    }
};
