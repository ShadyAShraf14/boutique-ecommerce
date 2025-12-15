<?php

// app/Models/City.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        // through state
        return $this->state?->country();
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
