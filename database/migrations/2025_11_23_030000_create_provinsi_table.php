<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('provinsi')) {
            Schema::create('provinsi', function (Blueprint $table) {
                $table->id();
                $table->string('kode_provinsi', 50)->nullable();
                $table->string('nama_provinsi', 150)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('provinsi');
    }
};
