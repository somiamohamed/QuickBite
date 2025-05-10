<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'foods' => $this->foods->map(function ($food) {
                return [
                    'name' => $food->name,
                    'quantity' => $food->pivot->quantity,
                    'price' => $food->pivot->price,
                ];
            }),
        ];
    }
}