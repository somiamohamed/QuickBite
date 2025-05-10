<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    public function restaurant(): HasOne
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    use HasApiTokens, HasFactory, Notifiable;

 
    const ROLE_CUSTOMER = 'customer';
    const ROLE_RESTAURANT = 'restaurant';
    const ROLE_DELIVERY = 'delivery';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role', 
        'address',
    ];

    protected $hidden = [
        'password',
    ];

  
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}