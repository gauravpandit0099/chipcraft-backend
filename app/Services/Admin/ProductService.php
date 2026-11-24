<?php

namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /* list products with search and filters */
    public function list(array $filters): LengthAwarePaginator
    {
        try {
            $query = Product::with(['category:id,name', 'subcategory:id,name']);
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }
            if (!empty($filters['category_id']) && $filters['category_id'] !== 'all') {
                $query->where('category_id', (int) $filters['category_id']);
            }
            if (isset($filters['in_stock']) && $filters['in_stock'] !== '' && $filters['in_stock'] !== 'all') {
                $query->where('in_stock', (bool) $filters['in_stock']);
            }
            $perPage = (int) ($filters['per_page'] ?? 8);
            return $query->latest()->paginate($perPage);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* find single product by id */
    public function find(int $id): ?Product
    {
        try {
            return Product::with(['category:id,name', 'subcategory:id,name'])->find($id);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /* create product with cover and gallery */
    public function create(array $data, Request $request): Product
    {
        DB::beginTransaction();
        try {
            $coverPath = $request->file('cover_image')->store('products/covers', 'public');
            $galleryPaths = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $galleryPaths[] = $file->store('products/gallery', 'public');
                }
            }
            $product = Product::create([
                'category_id' => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'in_stock' => $data['in_stock'],
                'image' => $coverPath,
                'gallery_images' => $galleryPaths,
            ]);
            DB::commit();
            return $product->load(['category:id,name', 'subcategory:id,name']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* update product and optional images */
    public function update(int $id, array $data, Request $request): ?Product
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            if (!$product) {
                DB::rollBack();
                return null;
            }
            $coverPath = $product->image;
            if ($request->hasFile('cover_image')) {
                if ($coverPath) {
                    Storage::disk('public')->delete($coverPath);
                }
                $coverPath = $request->file('cover_image')->store('products/covers', 'public');
            }
            $galleryPaths = $product->gallery_images ?? [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $galleryPaths[] = $file->store('products/gallery', 'public');
                }
            }
            $product->update([
                'category_id' => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'in_stock' => $data['in_stock'],
                'image' => $coverPath,
                'gallery_images' => $galleryPaths,
            ]);
            DB::commit();
            return $product->load(['category:id,name', 'subcategory:id,name']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* delete product and stored images */
    public function delete(int $id): bool
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            if (!$product) {
                DB::rollBack();
                return false;
            }
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            foreach ($product->gallery_images ?? [] as $img) {
                Storage::disk('public')->delete($img);
            }
            $product->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}