<?php

// app/Models/Room.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'capacity',
        'assigned_for',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}