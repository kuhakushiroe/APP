<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'wa',
        'role',
        'name',
        'username',
        'email',
        'password',
        'subrole',
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
     * Get the attributes that should be cast.
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
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function hasSubRole($subrole)
    {
        return $this->subrole === $subrole;
    }
    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }
    public function subRole($subroles)
    {
        return in_array($this->subroles, (array) $subroles);
    }
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'nrp', 'username');
    }
}
