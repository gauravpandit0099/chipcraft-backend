<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /* inject admin dashboard service */
    public function __construct(private DashboardService $dashboardService)
    {
    }

    /* return dynamic dashboard summary data */
    public function index(Request $request): JsonResponse
    {
        try {
            $period = in_array($request->query('period'), ['daily', 'weekly', 'monthly'])
                ? $request->query('period')
                : 'daily';
            $data = $this->dashboardService->getSummary($period);
            return ResponseHelper::success($data, 'Dashboard data fetched');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}