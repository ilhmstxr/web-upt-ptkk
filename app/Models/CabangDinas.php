<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabangDinas extends Model
{
    use HasFactory;

    protected $table = 'cabang_dinas';

    protected $fillable = [
        'nama',
        'alamat',
        'laman',
    ];
}
