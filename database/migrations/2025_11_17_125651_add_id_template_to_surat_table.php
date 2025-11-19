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
        Schema::table('surat', function (Blueprint $table) {
            $table->unsignedBigInteger('id_template')->nullable()->after('nomor_surat');
            $table->foreign('id_template')
                ->references('id')
                ->on('templates')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropForeign(['id_template']);
            $table->dropColumn('id_template');
        });
    }
};
