<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisiIdToPegawaisTable extends Migration
{
    public function up()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->foreignId('divisi_id')->nullable()->constrained('divisions')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['divisi_id']);
            $table->dropColumn('divisi_id');
        });
    }
}

