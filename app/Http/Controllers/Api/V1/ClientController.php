<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListClientsRequest;
use App\Http\Resources\Api\V1\ClientResource;
use App\Models\Client;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function index(ListClientsRequest $request): AnonymousResourceCollection
    {
        $result = Client::listBy(
            filters: $request->filters(),
            fields: $request->fields(),
            order: $request->order(),
            size: $request->size(),
        );

        return ClientResource::collection($result);
    }
}
