<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_ON_THE_WAY = 'on_the_way';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'total_price',
        'status',
        'delivery_address',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'order_food')
            ->withPivot('quantity', 'price');
    }
    
}