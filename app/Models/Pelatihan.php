<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pelatihan extends Model
{
    protected $fillable = [
        'judul', 'slug', 'gambar',
        'tanggal_mulai', 'tanggal_selesai', 'deskripsi'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->judul);
        });
    }
}
