<?php

use App\Models\Food;
use App\Models\Order;
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
        if (!Schema::hasTable('order_food')) {
            Schema::create('order_food', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Order::class)->constrained()->onDelete('cascade');
                $table->foreignIdFor(Food::class)->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('price', 8, 2);
                $table->json('selected_options_payload')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_food');
    }
};
