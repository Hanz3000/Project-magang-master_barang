<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawais', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('divisi_id');
                $table->foreign('user_id', 'pegawais_user_id_foreign')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('pegawais', 'user_id')) {
                $table->dropForeign('pegawais_user_id_foreign');
                $table->dropColumn('user_id');
            }
        });
    }
};
