<?php

namespace App\Services\Admin;

use App\Models\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubcategoryService
{
    /* list subcategories with filters */
    public function list(array $filters): LengthAwarePaginator
    {
        try {
            $query = Subcategory::with('category:id,name')->withCount('products');
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }
            if (!empty($filters['category_id']) && $filters['category_id'] !== 'all') {
                $query->where('category_id', (int) $filters['category_id']);
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

    /* create new subcategory record */
    public function create(array $data): Subcategory
    {
        DB::beginTransaction();
        try {
            $subcategory = Subcategory::create([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'status' => $data['status'],
            ]);
            DB::commit();
            return $subcategory->load('category:id,name');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* update existing subcategory record */
    public function update(int $id, array $data): ?Subcategory
    {
        DB::beginTransaction();
        try {
            $subcategory = Subcategory::find($id);
            if (!$subcategory) {
                DB::rollBack();
                return null;
            }
            $subcategory->update([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'status' => $data['status'],
            ]);
            DB::commit();
            return $subcategory->load('category:id,name');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* delete subcategory by id */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $subcategory = Subcategory::find($id);
            if (!$subcategory) {
                DB::rollBack();
                return false;
            }
            $subcategory->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}