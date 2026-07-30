<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelompok7', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); 
            $table->string('password');
            $table->string('nama')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelompok7');
    }
};
