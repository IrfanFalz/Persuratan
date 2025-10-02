<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_dispensasi', function (Blueprint $table) {
            $table->increments('id_sd');
            $table->unsignedInteger('id_surat');
            $table->unsignedInteger('id_persetujuan');
            $table->text('keperluan')->nullable();
            $table->string('lampiran', 255)->nullable();
            $table->string('tempat', 200)->nullable();
            $table->date('tanggal')->nullable();
            $table->time('jam')->nullable();
            $table->string('hari', 20)->nullable();

            $table->index('id_surat');
            $table->index('id_persetujuan');

            $table->foreign('id_surat')->references('id_surat')->on('surat')->onDelete('cascade');
            $table->foreign('id_persetujuan')->references('id_persetujuan')->on('persetujuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_dispensasi');
    }
};
