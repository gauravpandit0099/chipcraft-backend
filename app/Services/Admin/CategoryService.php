<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService
{
    /* list categories with search and filter */
    public function list(array $filters): LengthAwarePaginator
    {
        try {
            $query = Category::withCount('products');
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }
            if (isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== 'all') {
                $query->where('status', (bool) $filters['status']);
            }
            $perPage = (int) ($filters['per_page'] ?? 8);
            return $query->latest()->paginate($perPage);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* create new category record */
    public function create(array $data): Category
    {
        DB::beginTransaction();
        try {
            $category = Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'status' => $data['status'],
            ]);
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* update existing category record */
    public function update(int $id, array $data): ?Category
    {
        DB::beginTransaction();
        try {
            $category = Category::find($id);
            if (!$category) {
                DB::rollBack();
                return null;
            }
            $category->update([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'status' => $data['status'],
            ]);
            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* delete category by id */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $category = Category::find($id);
            if (!$category) {
                DB::rollBack();
                return false;
            }
            $category->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}