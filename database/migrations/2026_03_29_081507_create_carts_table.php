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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->decimal('total_price', 8, 2);
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('product_size_id')->nullable();
            $table->unsignedBigInteger('product_color_id')->nullable();

            /* 
                The composite foreign keys ensure the selected product_size_id or product_color_id belongs to the specified product_id.
                Prevents assigning a size or a color to a product unless that size or color actually belongs to the product.
            */

            $table->foreign(['product_size_id', 'product_id'])
                ->references(['id', 'product_id'])
                ->on('product_sizes')
                ->cascadeOnDelete();

            $table->foreign(['product_color_id', 'product_id'])
                ->references(['id', 'product_id'])
                ->on('product_colors')
                ->cascadeOnDelete();

            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('cart_items');
    }
};
