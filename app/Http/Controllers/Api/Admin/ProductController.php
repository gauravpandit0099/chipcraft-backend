<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /* inject admin product service */
    public function __construct(private ProductService $productService)
    {
    }

    /* fetch paginated product list */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->productService->list($request->only(['search', 'category_id', 'in_stock', 'per_page']));
            return ResponseHelper::success($data, 'Products fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* fetch single product detail */
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->find($id);
            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }
            return ResponseHelper::success($product, 'Product fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* store new product with images */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->create($request->validated(), $request);
            return ResponseHelper::success($product, 'Product created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* update existing product */
    public function update(UpateProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->update($id, $request->validated(), $request);
            if (!$product) {
                return ResponseHelper::error('Product not found', 404);
            }
            return ResponseHelper::success($product, 'Product updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* delete product by id */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->productService->delete($id);
            if (!$deleted) {
                return ResponseHelper::error('Product not found', 404);
            }
            return ResponseHelper::success(null, 'Product deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}