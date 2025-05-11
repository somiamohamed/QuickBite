<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("food_options", function (Blueprint $table) {
            $table->id();
            $table->foreignId("option_group_id")->constrained()->onDelete("cascade");
            $table->string("name"); // e.g., "Small", "Extra Cheese"
            $table->decimal("price_adjustment", 8, 2)->default(0.00);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_options');
    }
};
