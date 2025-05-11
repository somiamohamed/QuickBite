<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price, 
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'restaurant_id' => $this->restaurant_id,
            // load option_groups with their food_options in the controller
            'option_groups' => OptionGroupResource::collection($this->whenLoaded('optionGroups')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}