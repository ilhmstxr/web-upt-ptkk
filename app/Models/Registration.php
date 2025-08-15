<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'address',
        'phone',
        'email',
        'school_name',
        'school_address',
        'competence',
        'class',
        'dinas_branch',
        'ktp_path',
        'ijazah_path',
        'surat_tugas_path',
        'surat_sehat_path',
        'surat_tugas_nomor',
        'pas_foto_path',
        'room_id',
    ];
    
    /**
     * Get the room that the registration belongs to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}