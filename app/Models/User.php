<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Relasi ke Biodata Diri
     */
    public function biodataDiri()
    {
        return $this->hasOne(BiodataDiri::class);
    }

    /**
     * Relasi ke Biodata Sekolah
     */
    public function biodataSekolah()
    {
        return $this->hasOne(BiodataSekolah::class);
    }

    /**
     * Relasi ke Biodata Dokumen
     */
    public function biodataDokumen()
    {
        return $this->hasOne(BiodataDokumen::class);
    }
}
