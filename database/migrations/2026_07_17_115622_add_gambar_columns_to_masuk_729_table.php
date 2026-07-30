<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('masuk_729', function (Blueprint $table) {
            $table->string('gambar')->nullable()->after('keterangan');
            $table->string('gambar_original')->nullable()->after('gambar');
        });
    }

    public function down()
    {
        Schema::table('masuk_729', function (Blueprint $table) {
            $table->dropColumn(['gambar', 'gambar_original']);
        });
    }
};
