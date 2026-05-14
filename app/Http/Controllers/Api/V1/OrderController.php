<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = DB::transaction(function () use ($request): Order {
            $order = Order::create($request->orderData());

            $distributor_id = $order->distributor_id;

            $order->orderItems()->createMany(
                array_map(
                    fn (array $item): array => array_merge($item, [
                        'distributor_id' => $distributor_id,
                    ]),
                    $request->orderItems(),
                ),
            );

            return $order->refresh()->load('orderItems');
        });

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
