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
        Schema::table('nas', function (Blueprint $table) {
            // Agregar columnas faltantes si no existen
            if (!Schema::hasColumn('nas', 'host')) {
                $table->string('host', 128)->nullable()->after('server');
            }
            if (!Schema::hasColumn('nas', 'user')) {
                $table->string('user', 128)->nullable()->after('host');
            }
            if (!Schema::hasColumn('nas', 'pass')) {
                $table->string('pass', 255)->nullable()->after('user');
            }
            if (!Schema::hasColumn('nas', 'port')) {
                $table->integer('port')->nullable()->after('pass');
            }
            if (!Schema::hasColumn('nas', 'status')) {
                $table->string('status', 20)->default('activo')->after('port');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nas', function (Blueprint $table) {
            if (Schema::hasColumn('nas', 'host')) {
                $table->dropColumn('host');
            }
            if (Schema::hasColumn('nas', 'user')) {
                $table->dropColumn('user');
            }
            if (Schema::hasColumn('nas', 'pass')) {
                $table->dropColumn('pass');
            }
            if (Schema::hasColumn('nas', 'port')) {
                $table->dropColumn('port');
            }
            if (Schema::hasColumn('nas', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
