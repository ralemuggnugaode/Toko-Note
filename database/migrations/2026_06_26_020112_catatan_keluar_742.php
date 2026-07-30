<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_keluar_742', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('barangid_742')->nullable();
    $table->date('tanggal_742');
    $table->string('pihak_742');
    $table->string('nomor_742');
    $table->text('keterangan_742')->nullable();
    $table->json('items_742')->nullable();
    $table->integer('total_742');
    $table->string('gambar_742')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_keluar_742');
    }
};
