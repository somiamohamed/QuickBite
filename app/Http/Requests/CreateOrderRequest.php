<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:food,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.selected_options' => 'nullable|array', 
            'items.*.selected_options.*' => 'integer|exists:food_options,id',
        ];
    }
}
