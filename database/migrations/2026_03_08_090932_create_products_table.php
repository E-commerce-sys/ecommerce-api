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

            // stored computed column
            $table->decimal('effective_price', 8, 2)
                ->storedAs('CASE WHEN has_discount = true AND discount_percentage > 0
                    THEN ROUND((price * (1 - discount_percentage / 100.0))::numeric, 2)
                    ELSE price END');

            $table->index('effective_price');

            $table->boolean('is_best_selling')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_new')->default(false);
            $table->string('new_arrival_image')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('image');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex_code');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Composite keys
            $table->unique(['id', 'product_id']);

            $table->timestamps();
        });

        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size_label');
            $table->decimal('extra_price', 8, 2)->default(0);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Composite keys
            $table->unique(['id', 'product_id']);

            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('color_id')->constrained('product_colors')->onDelete('cascade');
            $table->foreignId('size_id')->constrained('product_sizes')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_colors');
        Schema::dropIfExists('product_sizes');
        Schema::dropIfExists('products');
    }
};
