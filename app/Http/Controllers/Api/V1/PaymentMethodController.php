<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListPaymentMethodsRequest;
use App\Http\Requests\Api\V1\UpdatePaymentMethodRequest;
use App\Http\Resources\Api\V1\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentMethodController extends Controller
{
    public function index(ListPaymentMethodsRequest $request): AnonymousResourceCollection
    {
        $result = PaymentMethod::listBy(
            filters: $request->filters(),
            fields: $request->fields(),
            relations: $request->relations(),
            order: $request->order(),
            size: $request->size(),
        );

        return PaymentMethodResource::collection($result);
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod): PaymentMethodResource
    {
        $paymentMethod->update($request->validated());

        return new PaymentMethodResource($paymentMethod);
    }
}
