<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    /** @use HasFactory<\Database\Factories\AsramaFactory> */
    use HasFactory;

    protected $table = 'asrama';

    protected $fillable = [
        'nama',
        'gender',
        'alamat',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }
}
