<?php

namespace App\Services\User;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserDashboardService
{
    /* fetch home categories and 5 sections */
    public function getHomeData(): array
    {
        try {
            $categories = Category::where('status', true)
                ->select('id', 'name', 'slug')
                ->get();
            $featuredProducts = Product::with('category:id,name,slug')
                ->latest()
                ->take(4)
                ->get();
            $categorySections = Category::where('status', true)
                ->whereHas('products')
                ->with(['products' => fn ($q) => $q->latest()->take(4)])
                ->take(5)
                ->get()
                ->map(fn ($cat) => [
                    'id' => $cat->id,
                    'title' => $cat->name,
                    'slug' => $cat->slug,
                    'products' => $cat->products,
                ]);
            return [
                'categories' => $categories,
                'featured_products' => $featuredProducts,
                'category_sections' => $categorySections,
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* list all products with public filters */
    public function getProducts(array $filters): LengthAwarePaginator
    {
        try {
            $query = Product::with(['category:id,name,slug', 'subcategory:id,name,slug']);
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }
            if (!empty($filters['category']) && $filters['category'] !== 'all') {
                $slug = $filters['category'];
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            }
            if (isset($filters['in_stock']) && $filters['in_stock'] !== '' && $filters['in_stock'] !== 'all') {
                $query->where('in_stock', (bool) $filters['in_stock']);
            }
            return $query->latest()->paginate((int) ($filters['per_page'] ?? 12));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}