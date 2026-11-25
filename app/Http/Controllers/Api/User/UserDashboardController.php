<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\User\UserDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    /* inject public storefront service */
    public function __construct(private UserDashboardService $storefrontService)
    {
    }

    /* return public storefront home data */
    public function home(): JsonResponse
    {
        try {
            $data = $this->storefrontService->getHomeData();
            return ResponseHelper::success($data, 'Storefront home loaded');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /* return paginated public products */
    public function products(Request $request): JsonResponse
    {
        try {
            $data = $this->storefrontService->getProducts($request->only(['search', 'category', 'in_stock', 'per_page']));
            return ResponseHelper::success($data, 'Products loaded');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}