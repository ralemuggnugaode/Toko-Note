<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('masuk_729', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('pihak');
            $table->string('nomor')->nullable();
            $table->json('items');
            $table->integer('total');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('masuk_729');
    }
};
