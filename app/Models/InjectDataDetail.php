<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InjectDataDetail extends Model
{
    use HasFactory;

    protected $table = 'inject_data_details';

    protected $guarded = [];

    public function injectData()
    {
        return $this->belongsTo(InjectData::class, 'unique_keys', 'unique_key');
    }
}
