<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
    protected $fillable = ["food_id", "name", "type", "required"];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function foodOptions()
    {
        return $this->hasMany(FoodOption::class);
    }
}
