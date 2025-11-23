<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to existing `guru` table if they don't exist yet
        Schema::table('guru', function (Blueprint $table) {
            if (! Schema::hasColumn('guru', 'no_telp')) {
                $table->string('no_telp')->nullable()->after('nama');
            }
            if (! Schema::hasColumn('guru', 'id_pengguna')) {
                $table->unsignedInteger('id_pengguna')->nullable()->after('no_telp');
            }
            if (! Schema::hasColumn('guru', 'created_at') || ! Schema::hasColumn('guru', 'updated_at')) {
                $table->timestamps();
            }
        });

        // Ensure an index on `nip` exists (use INFORMATION_SCHEMA to avoid duplicate index errors)
        $connection = DB::connection();
        $dbName = $connection->getDatabaseName();

        $indexExists = $connection->selectOne(
            "SELECT COUNT(1) AS cnt FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?",
            [$dbName, 'guru', 'idx_guru_nip']
        );

        if (! $indexExists || ($indexExists && $indexExists->cnt == 0)) {
            Schema::table('guru', function (Blueprint $table) {
                $table->index('nip', 'idx_guru_nip');
            });
        }

        // Add foreign key to pengguna.id_pengguna if possible and not already present
        $hasPenggunaTable = Schema::hasTable('pengguna');
        $hasPenggunaId = $hasPenggunaTable && Schema::hasColumn('pengguna', 'id_pengguna');

        if ($hasPenggunaId && Schema::hasColumn('guru', 'id_pengguna')) {
            $fkExists = $connection->selectOne(
                "SELECT COUNT(1) AS cnt FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = ?",
                [$dbName, 'guru', 'fk_guru_pengguna']
            );

            if (! $fkExists || ($fkExists && $fkExists->cnt == 0)) {
                Schema::table('guru', function (Blueprint $table) {
                    $table->foreign('id_pengguna', 'fk_guru_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('set null');
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            if (Schema::hasColumn('guru', 'id_pengguna')) {
                // drop FK if exists
                try {
                    $table->dropForeign('fk_guru_pengguna');
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropColumn('id_pengguna');
                } catch (\Throwable $e) {
                }
            }

            if (Schema::hasColumn('guru', 'no_telp')) {
                try {
                    $table->dropColumn('no_telp');
                } catch (\Throwable $e) {
                }
            }

            if (Schema::hasColumn('guru', 'created_at') && Schema::hasColumn('guru', 'updated_at')) {
                try {
                    $table->dropColumn(['created_at', 'updated_at']);
                } catch (\Throwable $e) {
                }
            }

            // drop index if exists
            try {
                $table->dropIndex('idx_guru_nip');
            } catch (\Throwable $e) {
            }
        });
    }
};
