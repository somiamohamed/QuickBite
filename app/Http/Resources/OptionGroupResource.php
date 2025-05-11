<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type, 
            'required' => (bool) $this->required,
            'food_options' => FoodOptionResource::collection($this->whenLoaded('foodOptions')),
        ];
    }
}
