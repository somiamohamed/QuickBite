<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "description" => $this->description,
            "image" => $this->image ? asset("storage/" . $this->image) : null,
            "restaurant_id" => $this->restaurant_id,
            "option_groups" => OptionGroupResource::collection($this->whenLoaded("optionGroups")),
        ];
    }
}