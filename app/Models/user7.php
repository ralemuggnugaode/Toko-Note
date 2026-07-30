<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User7 extends Authenticatable
{
    use HasFactory;

    protected $table = 'kelompok7';
    protected $fillable = ['username', 'password', 'nama'];
    protected $hidden = ['password', 'remember_token'];
}
