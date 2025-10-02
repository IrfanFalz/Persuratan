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
        Schema::create('detai_spt', function (Blueprint $table) {
            $table->increments('id_Tdetail');
            $table->unsignedInteger('id_spt');
            $table->string('nama_guru', 100)->nullable();
            $table->string('nip', 30)->nullable();
            $table->text('keterangan')->nullable();

            $table->index('id_spt');
            $table->foreign('id_spt')->references('id_spt')->on('surat_perintah_tugas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detai_spt');
    }
};
