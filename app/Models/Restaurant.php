<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'name',
        'address',
        'description',
        'phone_number',
        'cuisine_type',
        'user_id', 
        'delivery_time' => '30-50',
        'min_order' => '50',
        'is_featured', 
        'is_popular',
    ];

 
    public function foods(): HasMany
    {
        return $this->hasMany(Food::class);
    }
 
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(Admin::class, 'owner_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_restaurant');
    }
}