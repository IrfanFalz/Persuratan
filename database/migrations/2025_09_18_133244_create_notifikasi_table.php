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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->increments('id_notifikasi');
            $table->unsignedInteger('id_surat');
            $table->unsignedInteger('id_pengguna');
            $table->text('pesan')->nullable();
            $table->enum('status', ['disetujui','selesai','ditolak'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('dibaca')->nullable();

            $table->index('id_surat');
            $table->index('id_pengguna');

            $table->foreign('id_surat')->references('id_surat')->on('surat')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
