<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            "items" => "required|array",
            "items.*.food_id" => "required|exists:food,id",
            "items.*.quantity" => "required|integer|min:1",
            "items.*.options" => "nullable|array",
            "items.*.options.*" => "integer|exists:food_options,id",
        ];
    }
}