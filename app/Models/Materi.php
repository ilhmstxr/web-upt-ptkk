<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'judul',
        'slug',
        'order',
        'deskripsi',
        'kategori',
        'durasi',
        'konten',
        'file_pendukung',
    ];

    protected $casts = [
        'durasi' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // accessor ringkasan
    public function getExcerptAttribute()
    {
        return strlen(strip_tags($this->konten ?? '')) > 120
            ? substr(strip_tags($this->konten), 0, 120) . '...'
            : strip_tags($this->konten);
    }

    public function progresses()
    {
        return $this->hasMany(\App\Models\MateriProgress::class, 'materi_id');
    }
}
