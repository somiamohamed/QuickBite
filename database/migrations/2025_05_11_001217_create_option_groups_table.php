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
        Schema::create("option_groups", function (Blueprint $table) {
            $table->id();
            $table->foreignId("food_id")->constrained()->onDelete("cascade");
            $table->string("name"); // e.g., "Size", "Select Toppings"
            $table->enum("type", ["radio", "checkbox", "quantity"])->default("radio"); // How to select options
            $table->boolean("required")->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('option_groups');
    }
};
