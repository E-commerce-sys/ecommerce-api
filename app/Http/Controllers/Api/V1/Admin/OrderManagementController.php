<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use App\States\Cancelled;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class OrderManagementController extends ApiController
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Order::class);

        return OrderResource::collection(
            QueryBuilder::for(Order::class)
                ->allowedIncludes(Order::allowedIncludes())
                ->allowedFilters(Order::allowedFilters())
                ->orderBy('created_at', 'asc')
                ->paginate(10)
        );
    }

    public function advanceStatus(Request $request): JsonResponse
    {
        $this->authorize('update', Order::class);

        $orderIds = $request->query('orderIds', '');
        $orderIds = array_filter(array_map('intval', explode(',', $orderIds)));
        $orders = Order::whereIn('id', $orderIds)->get();

        if (count($orders) !== count($orderIds)) {
            return $this->error('Invalid order ids.', 400);
        }

        DB::transaction(function () use ($orders): void {
            foreach ($orders as $order) {
                if (is_null($order->status::next())) {
                    abort(400, "Invalid order status transition for order {$order->id}");
                }

                $order->status->transitionTo($order->status::next());
            }
        });

        return $this->ok([], 'Orders went into next status successfully!');
    }

    public function cancel(int $orderId): JsonResponse
    {
        $this->authorize('update', Order::class);

        $order = Order::findOrFail($orderId);

        if (is_null($order->status::next())) {
            return $this->error('Admin cannot cancel order in '.class_basename($order->status).' status.', 400);
        }

        $order->status = Cancelled::class;
        $order->save();

        return $this->success(new OrderResource($order), 'Order cancelled by Admin.');
    }
}
