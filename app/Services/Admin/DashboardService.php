<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /* fetch kpi cards, 3 graphs, orders */
    public function getSummary(string $period = 'daily'): array
    {
        try {
            $stats = [
                'total_revenue' => (float) Order::where('status', '!=', 'Cancelled')->sum('total'),
                'total_orders' => Order::count(),
                'total_products' => Product::count(),
                'out_of_stock_count' => Product::where('in_stock', false)->count(),
                'total_users' => User::where('role', 'user')->count(),
            ];
            return [
                'stats' => $stats,
                'sales_overview' => $this->getSalesOverview($period),
                'order_status_chart' => $this->getOrderStatusCounts(),
                'category_products_chart' => $this->getCategoryProductCounts(),
                'recent_orders' => Order::with('user:id,name')
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(fn ($o) => [
                        'id' => $o->id,
                        'order_number' => $o->order_number,
                        'customer_name' => $o->user->name ?? 'Customer',
                        'total' => (float) $o->total,
                        'status' => $o->status,
                        'created_at' => $o->created_at->format('Y-m-d'),
                    ]),
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* build sales trend by period */
    private function getSalesOverview(string $period): array
    {
        try {
            $points = [];
            if ($period === 'weekly') {
                for ($i = 3; $i >= 0; $i--) {
                    $start = Carbon::now()->subWeeks($i)->startOfWeek();
                    $end = Carbon::now()->subWeeks($i)->endOfWeek();
                    $revenue = Order::whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'Cancelled')
                        ->sum('total');
                    $points[] = ['day' => 'Wk ' . $start->format('W'), 'revenue' => (float) $revenue];
                }
                return $points;
            }
            if ($period === 'monthly') {
                for ($i = 5; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $revenue = Order::whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->where('status', '!=', 'Cancelled')
                        ->sum('total');
                    $points[] = ['day' => $month->format('M'), 'revenue' => (float) $revenue];
                }
                return $points;
            }
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $revenue = Order::whereDate('created_at', $date)
                    ->where('status', '!=', 'Cancelled')
                    ->sum('total');
                $points[] = ['day' => $date->format('D'), 'revenue' => (float) $revenue];
            }
            return $points;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* get paste 7 days data */
    private function getLastSevenDaysSales(): array
    {
        try {
            $days = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $revenue = Order::whereDate('created_at', $date)
                    ->where('status', '!=', 'Cancelled')
                    ->sum('total');
                $days[] = [
                    'day' => $date->format('D'),
                    'revenue' => (float) $revenue,
                ];
            }
            return $days;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* count orders grouped by status */
    private function getOrderStatusCounts(): array
    {
        try {
            $statuses = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'];
            $counts = Order::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');
            return array_map(fn ($s) => [
                'status' => $s,
                'count' => (int) ($counts[$s] ?? 0),
            ], $statuses);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* count products as per category */
    private function getCategoryProductCounts(): array
    {
        try {
            return Category::withCount('products')
                ->get()
                ->map(fn ($c) => [
                    'name' => $c->name,
                    'count' => $c->products_count,
                ])
                ->toArray();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}