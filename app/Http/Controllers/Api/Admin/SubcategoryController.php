<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubcategoryRequest;
use App\Http\Requests\Admin\UpdateSubcategoryRequest;
use App\Services\Admin\SubcategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    /* inject admin subcategory service */
    public function __construct(private SubcategoryService $subcategoryService)
    {
    }

    /* fetch paginated subcategory list */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->subcategoryService->list($request->only(['search', 'category_id', 'status', 'per_page']));
            return ResponseHelper::success($data, 'Subcategories fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* store new subcategory */
    public function store(StoreSubcategoryRequest $request): JsonResponse
    {
        try {
            $subcategory = $this->subcategoryService->create($request->validated());
            return ResponseHelper::success($subcategory, 'Subcategory created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* update existing subcategory */
    public function update(UpdateSubcategoryRequest $request, int $id): JsonResponse
    {
        try {
            $subcategory = $this->subcategoryService->update($id, $request->validated());
            if (!$subcategory) {
                return ResponseHelper::error('Subcategory not found', 404);
            }
            return ResponseHelper::success($subcategory, 'Subcategory updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* delete subcategory by id */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->subcategoryService->delete($id);
            if (!$deleted) {
                return ResponseHelper::error('Subcategory not found', 404);
            }
            return ResponseHelper::success(null, 'Subcategory deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}