<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiodataSekolah extends Model
{
    protected $table = 'biodata_sekolah';

    protected $fillable = [
        'user_id',
        'school_name',
        'school_address',
        'competence',
        'class',
        'dinas_branch',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
