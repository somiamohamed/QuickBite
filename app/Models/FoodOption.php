<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodOption extends Model
{
    protected $fillable = ["option_group_id", "name", "price_adjustment"];
    public function optionGroup() { return $this->belongsTo(OptionGroup::class); }
}
