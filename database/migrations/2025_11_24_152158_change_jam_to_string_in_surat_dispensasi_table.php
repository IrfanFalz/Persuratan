<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->string('jam', 100)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->time('jam')->nullable()->change();
        });
    }
};
