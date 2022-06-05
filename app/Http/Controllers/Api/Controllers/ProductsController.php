<?php

/**
 * TODO: make it run like API, use ajax, not Http page redirect
 */

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Http\Controllers\Api\Requests\ProductsRequest;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductsController extends Controller
{
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function list(ProductsRequest $request)
    {
        try {
            $transformer = new ProductTransformer();
            $products = $this->productService->list();
            $productsTransformed = (new Collection($products))
                ->transform(function ($item) use ($transformer) {
                    return $transformer->transform($item);
                });
        } catch (Exception $e) {
            return response()->json(['result' => 0, 'message' => $e->getMessage()], $e->getCode());
        }
        return response()->json([
            'result' => 1,
            'data' => $productsTransformed
        ], 200);
    }

    public function new(ProductsRequest $request)
    {
        try {
            DB::beginTransaction();
            $newProduct = $this->productService->create(
                $request->name,
                $request->description,
                $request->image_url
            );
            $this->productService->updateTag($newProduct->id, $request->tags ?? "");
            $newProductTransformed = (new ProductTransformer())->transform($newProduct);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['result' => 0, 'message' => $e->getMessage()], $e->getCode());
        }
        return response()->json([
            'result' => 1,
            'data' => $newProductTransformed
        ], 200);
    }
}
