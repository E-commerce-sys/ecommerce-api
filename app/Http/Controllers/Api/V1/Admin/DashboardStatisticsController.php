<?php

/*
THIS FILE IS AI GENERATED
*/

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class DashboardStatisticsController extends ApiController
{
    private const LOW_STOCK_THRESHOLD = 5;

    public function __invoke(): JsonResponse
    {
        $this->authorize('viewAny', Order::class);

        return $this->success([
            'totalUsers' => User::count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => $this->totalRevenue(),
            'usersOverTime' => $this->monthlyTrend(User::query(), 'count(*)', 'users'),
            'ordersOverTime' => $this->monthlyTrend(Order::query(), 'count(*)', 'orders'),
            'revenueOverTime' => $this->monthlyTrend(Order::query(), 'coalesce(sum(total_price), 0)', 'revenue', true),
            'lowStockVariants' => $this->lowStockVariants(),
            'bestSellingProducts' => $this->bestSellingProducts(),
        ], 'Dashboard statistics fetched successfully!');
    }

    private function totalRevenue(): float
    {
        return round((float) Order::query()->sum('total_price'), 2);
    }

    /**
     * @return array<int, array{month: string, users?: int, orders?: int, revenue?: float}>
     */
    private function monthlyTrend(Builder $query, string $aggregate, string $valueKey, bool $isDecimal = false): array
    {
        $startOfWindow = now()->startOfMonth()->subMonths(11);
        $monthBucketExpression = $this->monthBucketExpression($query);

        $aggregatesByMonth = $query
            ->selectRaw("{$monthBucketExpression} as month_start, {$aggregate} as aggregate_value")
            ->where('created_at', '>=', $startOfWindow)
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get()
            ->mapWithKeys(function (object $row) use ($isDecimal): array {
                $value = $isDecimal
                    ? round((float) $row->aggregate_value, 2)
                    : (int) $row->aggregate_value;

                return [Carbon::parse($row->month_start)->format('Y-m') => $value];
            });

        return collect(range(11, 0))
            ->map(function (int $monthsAgo) use ($aggregatesByMonth, $valueKey, $isDecimal): array {
                $month = now()->startOfMonth()->subMonths($monthsAgo);
                $defaultValue = $isDecimal ? 0.0 : 0;

                return [
                    'month' => strtolower($month->format('M')),
                    $valueKey => $aggregatesByMonth->get($month->format('Y-m'), $defaultValue),
                ];
            })
            ->all();
    }

    private function monthBucketExpression(Builder $query): string
    {
        return match ($query->getModel()->getConnection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m-01 00:00:00', created_at)",
            'mysql' => "date_format(created_at, '%Y-%m-01 00:00:00')",
            default => "DATE_TRUNC('month', created_at)",
        };
    }

    /**
     * @return array<int, array{
     *     id: int,
     *     productId: int,
     *     productName: string,
     *     stock: int,
     *     color: string|null,
     *     size: string|null
     * }>
     */
    private function lowStockVariants(): array
    {
        return ProductVariant::query()
            ->with(['product', 'color', 'size'])
            ->where('stock', '<=', self::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->orderBy('id')
            ->get()
            ->map(function (ProductVariant $variant): array {
                return [
                    'id' => $variant->id,
                    'productId' => $variant->product_id,
                    'productName' => $variant->product?->name_en ?? '',
                    'stock' => (int) $variant->stock,
                    'color' => $variant->color?->name ?? $variant->color?->hex_code,
                    'size' => $variant->size?->size_label,
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array{id: int, name: string, amountSold: int}>
     */
    private function bestSellingProducts(): array
    {
        return Product::query()
            ->select('products.id', 'products.name_en')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as amount_sold')
            ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
            ->join('order_items', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->groupBy('products.id', 'products.name_en')
            ->orderByDesc('amount_sold')
            ->orderBy('products.id')
            ->limit(10)
            ->get()
            ->map(function (Product $product): array {
                return [
                    'id' => $product->id,
                    'name' => $product->name_en,
                    'amountSold' => (int) $product->amount_sold,
                ];
            })
            ->all();
    }
}
