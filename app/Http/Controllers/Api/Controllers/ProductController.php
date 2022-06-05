<?php

/**
 * TODO: make it run like API, use ajax, not Http page redirect
 */

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Transformers\ProductTransformer;
use App\Http\Controllers\Api\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductController extends Controller
{
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function get(ProductRequest $request)
    {
        try {
            $newProductTransformed = (new ProductTransformer())->transform($this->productService->get($request->id));
        } catch (Exception $e) {
            return response()->json(['result' => 0, 'message' => $e->getMessage()], $e->getCode());
        }
        return response()->json([
            'result' => 1,
            'data' => $newProductTransformed
        ], 200);
    }

    public function update(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->productService->update(
                $request->id,
                $request->name,
                $request->description,
                $request->image_url
            );
            $this->productService->updateTag($request->id, $request->tags);
            $newProductTransformed = (new ProductTransformer())->transform($this->productService->get($request->id));
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


    public function delete(ProductRequest $request, int $id)
    {
        try {
            $this->productService->delete($request->id);
        } catch (Exception $e) {
            return response()->json(['result' => 0, 'message' => $e->getMessage()], $e->getCode());
        }

        return response()->json(['result' => 1]);
    }
}
