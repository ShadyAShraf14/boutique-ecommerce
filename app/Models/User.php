<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'first_name',
    'last_name',
    'name',          // full name
    'username',
    'email',
    'mobile',
    'avatar',
    'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

        public function scopeCustomers($query)
    {
        return $query->role('customer'); // اسم رول العميل عندك
    }

    public function scopeSupervisors($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'supervisor']);
        });
    }


    public function addresses()
{
    return $this->hasMany(UserAddress::class);
}

}
