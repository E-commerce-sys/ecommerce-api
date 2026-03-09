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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_ku');
            $table->text('description_en');
            $table->text('description_ar');
            $table->text('description_ku');
            $table->foreignId('category_id')->constrained('categories')->onDelete('set null');
            $table->decimal('price', 8, 2);
            $table->boolean('has_discount')->default(false);
            $table->integer('discount_percentage')->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_best_selling')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_new')->default(false);
            $table->string('new_arrival_image')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
