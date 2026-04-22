<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListProductCategoriesRequest;
use App\Http\Resources\Api\V1\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    public function index(ListProductCategoriesRequest $request): AnonymousResourceCollection
    {
        $result = ProductCategory::listBy(
            filters: $request->filters(),
            fields: $request->fields(),
            relations: $request->relations(),
            order: $request->order(),
            size: $request->size(),
        );

        return ProductCategoryResource::collection($result);
    }
}
