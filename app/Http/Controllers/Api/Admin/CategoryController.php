<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Services\Admin\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /* inject admin category service */
    public function __construct(private CategoryService $categoryService)
    {
    }

    /* fetch paginated category list */
    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->categoryService->list($request->only(['search', 'status', 'per_page']));
            return ResponseHelper::success($data, 'Categories fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* store new category */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryService->create($request->validated());
            return ResponseHelper::success($category, 'Category created successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* update existing category */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->update($id, $request->validated());
            if (!$category) {
                return ResponseHelper::error('Category not found', 404);
            }
            return ResponseHelper::success($category, 'Category updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* delete category by id */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->categoryService->delete($id);
            if (!$deleted) {
                return ResponseHelper::error('Category not found', 404);
            }
            return ResponseHelper::success(null, 'Category deleted successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}