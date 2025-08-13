<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiodataDiri extends Model
{
    protected $table = 'biodata_diri';

    protected $fillable = [
        'user_id',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
