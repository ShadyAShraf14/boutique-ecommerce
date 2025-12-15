<?php

// app/Models/Country.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

public function states()
{
    return $this->hasMany(State::class);
}

public function cities()
{
    // cities من خلال states
    return $this->hasManyThrough(City::class, State::class);
}

public function addresses()
{
    return $this->hasMany(UserAddress::class);
}
}
