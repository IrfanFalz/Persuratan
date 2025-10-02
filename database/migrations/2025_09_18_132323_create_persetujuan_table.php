<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persetujuan', function (Blueprint $table) {
            $table->increments('id_persetujuan');
            $table->unsignedInteger('id_surat')->index();
            $table->unsignedInteger('id_pengguna')->index();
            $table->timestamp('timestamp')->useCurrent();
            $table->text('catatan')->nullable();
            $table->enum('disetujui', ['ya','tidak'])->nullable();

            $table->foreign('id_surat')
                ->references('id_surat')
                ->on('surat')
                ->onDelete('cascade');

            $table->foreign('id_pengguna')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persetujuan');
    }
};
