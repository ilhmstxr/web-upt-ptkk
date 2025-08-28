<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PivotJawaban extends Model
{
    protected $table = 'pivot_jawaban';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pertanyaan_id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pertanyaan_id',
        'template_pertanyaan_id',
    ];

    /**
     * Mendapatkan pertanyaan "anak" yang menggunakan template ini.
     */
    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    /**
     * Mendapatkan pertanyaan "master" yang menjadi template.
     */
    public function templatePertanyaan(): BelongsTo
    {
        return $this->belongsTo(Pertanyaan::class, 'template_pertanyaan_id');
    }
}
