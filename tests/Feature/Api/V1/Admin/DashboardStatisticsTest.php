<?php

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

it('returns dashboard statistics for admins', function () {
    Carbon::setTestNow('2026-04-23 12:00:00');
    $suffix = (string) str()->uuid();

    $admin = User::query()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => "admin-{$suffix}@example.com",
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
    ]);

    Permission::firstOrCreate(['name' => 'product:list', 'guard_name' => 'web']);
    $admin->givePermissionTo('product:list');

    Sanctum::actingAs($admin);

    $baselineResponse = $this->getJson('/api/admin/dashboard-statistics');
    $baselineResponse->assertOk();
    $baseline = $baselineResponse->json('data');

    $januaryUser = User::query()->create([
        'first_name' => 'January',
        'last_name' => 'User',
        'email' => "january-{$suffix}@example.com",
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'created_at' => now()->startOfYear()->addDays(2),
    ]);

    $februaryUser = User::query()->create([
        'first_name' => 'February',
        'last_name' => 'User',
        'email' => "february-{$suffix}@example.com",
        'email_verified_at' => now(),
        'password' => bcrypt('password'),
        'created_at' => now()->startOfYear()->addMonth()->addDays(3),
    ]);

    $category = Category::query()->create([
        'name_en' => "Electronics {$suffix}",
        'name_ar' => "إلكترونيات {$suffix}",
        'name_ku' => "ئەلیکترۆنیات {$suffix}",
        'icon' => 'category.png',
        'parent_id' => null,
    ]);

    $topProduct = Product::query()->create([
        'name_en' => 'Phone Case',
        'name_ar' => 'غطاء هاتف',
        'name_ku' => 'کاوری مۆبایل',
        'description_en' => 'Phone case',
        'description_ar' => 'غطاء هاتف',
        'description_ku' => 'کاوری مۆبایل',
        'category_id' => $category->id,
        'price' => 20,
        'has_discount' => false,
        'discount_percentage' => 0,
        'has_size' => true,
        'has_color' => true,
        'is_best_selling' => true,
        'is_featured' => false,
        'is_new_arrival' => false,
        'is_new' => false,
        'new_arrival_image' => null,
        'average_rating' => 4.5,
        'rating_count' => 10,
    ]);

    $secondaryProduct = Product::query()->create([
        'name_en' => 'USB-C Charger',
        'name_ar' => 'شاحن USB-C',
        'name_ku' => 'شاجنی USB-C',
        'description_en' => 'USB-C charger',
        'description_ar' => 'شاحن USB-C',
        'description_ku' => 'شاجنی USB-C',
        'category_id' => $category->id,
        'price' => 35,
        'has_discount' => false,
        'discount_percentage' => 0,
        'has_size' => false,
        'has_color' => false,
        'is_best_selling' => false,
        'is_featured' => false,
        'is_new_arrival' => false,
        'is_new' => false,
        'new_arrival_image' => null,
        'average_rating' => 4.2,
        'rating_count' => 5,
    ]);

    $black = ProductColor::query()->create([
        'name' => 'Black',
        'hex_code' => '#000000',
        'product_id' => $topProduct->id,
    ]);

    $small = ProductSize::query()->create([
        'name' => 'Small',
        'size_label' => 'S',
        'extra_price' => 0,
        'product_id' => $topProduct->id,
    ]);

    $lowStockVariant = ProductVariant::query()->create([
        'product_id' => $topProduct->id,
        'color_id' => $black->id,
        'size_id' => $small->id,
        'stock' => 3,
    ]);

    $healthyStockVariant = ProductVariant::query()->create([
        'product_id' => $secondaryProduct->id,
        'color_id' => null,
        'size_id' => null,
        'stock' => 20,
    ]);

    $shippingAddress = Address::query()->create([
        'user_id' => $januaryUser->id,
        'address_name' => 'Home',
        'street_name' => 'Main Street',
        'city' => 'Baghdad',
        'house_number' => '12',
        'state' => 'Baghdad',
        'country' => 'Iraq',
        'zip_code' => '10001',
    ]);

    $februaryOrder = Order::query()->create([
        'user_id' => $januaryUser->id,
        'shipping_address_id' => $shippingAddress->id,
        'shipping_cost' => 0,
        'discount_percentage' => 0,
        'subtotal' => 100,
        'total_price' => 100,
        'created_at' => now()->startOfYear()->addMonth()->addDays(4),
    ]);

    $marchOrder = Order::query()->create([
        'user_id' => $februaryUser->id,
        'shipping_address_id' => $shippingAddress->id,
        'shipping_cost' => 0,
        'discount_percentage' => 0,
        'subtotal' => 120,
        'total_price' => 120,
        'created_at' => now()->startOfYear()->addMonths(2)->addDays(5),
    ]);

    OrderItem::query()->create([
        'order_id' => $februaryOrder->id,
        'product_variant_id' => $lowStockVariant->id,
        'quantity' => 7,
        'unit_price' => 20,
    ]);

    OrderItem::query()->create([
        'order_id' => $marchOrder->id,
        'product_variant_id' => $healthyStockVariant->id,
        'quantity' => 2,
        'unit_price' => 35,
    ]);

    $response = $this->getJson('/api/admin/dashboard-statistics');

    $response
        ->assertOk()
        ->assertJsonPath('message', 'Dashboard statistics fetched successfully!');

    $payload = $response->json('data');
    $baselineUsersOverTime = collect($baseline['usersOverTime'])->keyBy('month');
    $baselineOrdersOverTime = collect($baseline['ordersOverTime'])->keyBy('month');
    $baselineRevenueOverTime = collect($baseline['revenueOverTime'])->keyBy('month');
    $usersOverTime = collect($payload['usersOverTime'])->keyBy('month');
    $ordersOverTime = collect($payload['ordersOverTime'])->keyBy('month');
    $revenueOverTime = collect($payload['revenueOverTime'])->keyBy('month');

    expect($payload['usersOverTime'])->toHaveCount(12);
    expect($payload['totalUsers'])->toBe($baseline['totalUsers'] + 2);
    expect($payload['totalOrders'])->toBe($baseline['totalOrders'] + 2);
    expect((float) $payload['totalRevenue'])->toEqual((float) $baseline['totalRevenue'] + 220.0);
    expect($usersOverTime->get('jan')['users'])->toBe($baselineUsersOverTime->get('jan')['users'] + 1);
    expect($usersOverTime->get('feb')['users'])->toBe($baselineUsersOverTime->get('feb')['users'] + 1);
    expect($ordersOverTime->get('feb')['orders'])->toBe($baselineOrdersOverTime->get('feb')['orders'] + 1);
    expect($ordersOverTime->get('mar')['orders'])->toBe($baselineOrdersOverTime->get('mar')['orders'] + 1);
    expect((float) $revenueOverTime->get('feb')['revenue'])->toEqual((float) $baselineRevenueOverTime->get('feb')['revenue'] + 100.0);
    expect((float) $revenueOverTime->get('mar')['revenue'])->toEqual((float) $baselineRevenueOverTime->get('mar')['revenue'] + 120.0);
    expect(collect($payload['lowStockVariants'])->contains(fn (array $variant): bool => $variant['productName'] === 'Phone Case'
        && $variant['color'] === 'Black'
        && $variant['size'] === 'S'
        && $variant['stock'] === 3))->toBeTrue();
    expect(collect($payload['bestSellingProducts'])->contains(fn (array $product): bool => $product['name'] === 'Phone Case'
        && $product['amountSold'] === 7))->toBeTrue();
    expect(collect($payload['bestSellingProducts'])->contains(fn (array $product): bool => $product['name'] === 'USB-C Charger'
        && $product['amountSold'] === 2))->toBeTrue();
});
