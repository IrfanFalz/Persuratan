<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('guru')) {
            Schema::create('guru', function (Blueprint $table) {
                $table->increments('id_guru');
                $table->string('nip')->unique();
                $table->string('nama');
                $table->string('no_telp')->nullable();
                $table->unsignedInteger('id_pengguna')->nullable();
                $table->timestamps();

                $table->index('nip');
                $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
