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
        Schema::create('stok_barang_719', function (Blueprint $table) {
            $table->id();
            $table->string('719_kode', 10)->unique();
            $table->text('719_gambar');
            $table->string('719_nama');
            $table->string('719_kategori');
            $table->decimal('719_harga_beli', 10, 2);
            $table->decimal('719_harga_jual', 10, 2);
            $table->integer('719_stok_min');
            $table->integer('719_stok_tercatat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barang_719');
    }
};
