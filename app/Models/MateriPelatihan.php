<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriPelatihan extends Model
{
    use HasFactory;

    protected $table = 'materi_pelatihan';

    protected $fillable = [
        'pelatihan_id',
        'kompetensi_pelatihan_id',
        'kompetensi_id',

        'judul',
        'tipe',
        'deskripsi',

        'file_path',
        'video_url',
        'link_url',
        'teks',

        'urutan',
        'estimasi_menit',
        'is_published',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'estimasi_menit' => 'integer',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * =========================
     * RELATIONS
     * =========================
     */

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function kompetensiPelatihan()
    {
        return $this->belongsTo(KompetensiPelatihan::class, 'kompetensi_pelatihan_id');
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    public function progresses()
    {
        return $this->hasMany(MateriProgress::class, 'materi_id');
    }

    /**
     * =========================
     * ACCESSORS
     * =========================
     */

    // ringkasan teks/deskripsi untuk preview
    public function getExcerptAttribute()
    {
        $text  = $this->deskripsi ?? $this->teks ?? '';
        $plain = strip_tags($text);

        return strlen($plain) > 120
            ? substr($plain, 0, 120) . '...'
            : $plain;
    }

    public function setVideoUrlAttribute($value)
    {
        if (!$value) {
            $this->attributes['video_url'] = null;
            return;
        }

        $value = trim($value);

        // youtube.com/watch?v=xxxx
        if (preg_match('~watch\?v=([^&]+)~', $value, $m)) {
            $this->attributes['video_url'] =
                'https://www.youtube.com/embed/' . $m[1];
            return;
        }

        // youtu.be/xxxx
        if (preg_match('~youtu\.be/([^?\&]+)~', $value, $m)) {
            $this->attributes['video_url'] =
                'https://www.youtube.com/embed/' . $m[1];
            return;
        }

        // kalau sudah embed atau link lain
        $this->attributes['video_url'] = $value;
    }

}
