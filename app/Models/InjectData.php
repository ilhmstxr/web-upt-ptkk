<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InjectData extends Model
{
    use HasFactory;

    protected $table = 'inject_data';

    protected $primaryKey = 'unique_key';

    public $incrementing = false;

    protected $guarded = [];

    public function details()
    {
        // 'unique_keys' is the foreign key in inject_data_details table
        // 'unique_key' is the local key in inject_data table
        return $this->hasMany(InjectDataDetail::class, 'unique_keys', 'unique_key');
    }

    public function kompetensiPelatihan()
    {
        return $this->belongsTo(KompetensiPelatihan::class, 'kompetensi_id');
    }
}
