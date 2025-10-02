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
        Schema::create('detail_dispensasi', function (Blueprint $table) {
            $table->increments('id_Ddetail');
            $table->unsignedInteger('id_sd');
            $table->string('nama_siswa', 100)->nullable();
            $table->string('nisn', 30)->nullable();
            $table->string('kelas', 50)->nullable();

            $table->index('id_sd');
            $table->foreign('id_sd')->references('id_sd')->on('surat_dispensasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_dispensasi');
    }
};
