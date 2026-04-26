<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;

beforeEach(function (): void {
    Storage::fake('s3');

    if (! Schema::hasTable('users')) {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('categories')) {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name_en')->unique();
            $table->string('name_ar')->unique();
            $table->string('name_ku')->unique();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('products')) {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('permissions')) {
        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });
    }

    if (! Schema::hasTable('roles')) {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });
    }

    if (! Schema::hasTable('model_has_permissions')) {
        Schema::create('model_has_permissions', function (Blueprint $table): void {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->primary(['permission_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
        });
    }

    if (! Schema::hasTable('model_has_roles')) {
        Schema::create('model_has_roles', function (Blueprint $table): void {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->primary(['role_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
        });
    }

    if (! Schema::hasTable('role_has_permissions')) {
        Schema::create('role_has_permissions', function (Blueprint $table): void {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    Category::query()->delete();
    User::query()->delete();
    Permission::query()->delete();

    $admin = User::query()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin-'.str()->uuid().'@example.com',
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
    ]);

    foreach (['update', 'delete', 'category:update', 'category:delete'] as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    $admin->givePermissionTo(['update', 'delete', 'category:update', 'category:delete']);

    Sanctum::actingAs($admin);
});

it('deletes the old category icon from s3 when replacing it', function (): void {
    $suffix = (string) fake()->unique()->numberBetween(1000, 9999);

    $parentCategory = Category::query()->create([
        'name_en' => 'Parent '.$suffix,
        'name_ar' => 'تصنيف رئيسي '.$suffix,
        'name_ku' => 'هاوپۆل '.$suffix,
        'parent_id' => null,
    ]);

    $oldIconPath = 'all-images/subcategory-icons/old-icon.png';
    Storage::disk('s3')->put($oldIconPath, 'old-icon');

    $category = Category::query()->create([
        'name_en' => 'Child '.$suffix,
        'name_ar' => 'تصنيف فرعي '.$suffix,
        'name_ku' => 'لاپەڕە '.$suffix,
        'icon' => 'https://bucket.test/'.$oldIconPath,
        'parent_id' => $parentCategory->id,
    ]);

    $response = $this->patch(
        "/api/admin/categories/{$category->id}",
        [
            'data' => [
                'attributes' => [
                    'icon' => UploadedFile::fake()->image('replacement.png'),
                ],
            ],
        ],
        ['Accept' => 'application/json']
    );

    $response->assertOk();

    $updatedCategory = $category->fresh();
    $storedIcons = Storage::disk('s3')->allFiles('all-images/subcategory-icons');

    expect($updatedCategory->icon)->not->toBe('https://bucket.test/'.$oldIconPath);
    expect($storedIcons)->toHaveCount(1);
    expect($storedIcons[0])->not->toBe($oldIconPath);

    Storage::disk('s3')->assertMissing($oldIconPath);
    Storage::disk('s3')->assertExists($storedIcons[0]);
});

it('deletes category icons from s3 when deleting a category tree', function (): void {
    $suffix = (string) fake()->unique()->numberBetween(1000, 9999);

    $parentCategory = Category::query()->create([
        'name_en' => 'Root '.$suffix,
        'name_ar' => 'جذر '.$suffix,
        'name_ku' => 'ڕەگ '.$suffix,
        'parent_id' => null,
    ]);

    $childIconPath = 'all-images/subcategory-icons/child-icon.png';
    $grandchildIconPath = 'all-images/subcategory-icons/grandchild-icon.png';

    Storage::disk('s3')->put($childIconPath, 'child-icon');
    Storage::disk('s3')->put($grandchildIconPath, 'grandchild-icon');

    $childCategory = Category::query()->create([
        'name_en' => 'Child '.$suffix,
        'name_ar' => 'فرع '.$suffix,
        'name_ku' => 'لق '.$suffix,
        'icon' => 'https://bucket.test/'.$childIconPath,
        'parent_id' => $parentCategory->id,
    ]);

    $grandchildCategory = Category::query()->create([
        'name_en' => 'Grandchild '.$suffix,
        'name_ar' => 'حفيد '.$suffix,
        'name_ku' => 'نووە '.$suffix,
        'icon' => 'https://bucket.test/'.$grandchildIconPath,
        'parent_id' => $childCategory->id,
    ]);

    $response = $this->deleteJson("/api/admin/categories/{$parentCategory->id}");

    $response
        ->assertOk()
        ->assertJsonPath('message', 'Category deleted successfully!');

    Storage::disk('s3')->assertMissing($childIconPath);
    Storage::disk('s3')->assertMissing($grandchildIconPath);

    $this->assertDatabaseMissing('categories', ['id' => $parentCategory->id]);
    $this->assertDatabaseMissing('categories', ['id' => $childCategory->id]);
    $this->assertDatabaseMissing('categories', ['id' => $grandchildCategory->id]);
});
