<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardSeeder extends Seeder
{
    /* seed sample data for dashboard */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $user = User::firstOrCreate(
                ['email' => 'customer@chipcraft.com'],
                ['name' => 'Aarav Sharma', 'password' => 'user123', 'role' => 'user']
            );
            $catAudio = Category::firstOrCreate(['slug' => 'audio'], ['name' => 'Audio', 'status' => true]);
            $catWear = Category::firstOrCreate(['slug' => 'wearables'], ['name' => 'Wearables', 'status' => true]);
            $catAcc = Category::firstOrCreate(['slug' => 'accessories'], ['name' => 'Accessories', 'status' => true]);
            $subAudio = Subcategory::firstOrCreate(['slug' => 'earbuds'], ['category_id' => $catAudio->id, 'name' => 'Earbuds', 'status' => true]);
            $subWear = Subcategory::firstOrCreate(['slug' => 'smartwatches'], ['category_id' => $catWear->id, 'name' => 'Smartwatches', 'status' => true]);
            $subAcc = Subcategory::firstOrCreate(['slug' => 'chargers'], ['category_id' => $catAcc->id, 'name' => 'Chargers', 'status' => true]);
            Product::firstOrCreate(['slug' => 'chipcraft-pods-pro'], ['category_id' => $catAudio->id, 'subcategory_id' => $subAudio->id, 'name' => 'Chipcraft Pods Pro', 'price' => 4999, 'in_stock' => true]);
            Product::firstOrCreate(['slug' => 'pulse-watch-x'], ['category_id' => $catWear->id, 'subcategory_id' => $subWear->id, 'name' => 'Pulse Watch X', 'price' => 9999, 'in_stock' => true]);
            Product::firstOrCreate(['slug' => 'gan-65w-charger'], ['category_id' => $catAcc->id, 'subcategory_id' => $subAcc->id, 'name' => 'GaN 65W Charger', 'price' => 1999, 'in_stock' => false]);
            $statuses = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Delivered', 'Shipped', 'Pending'];
            foreach ($statuses as $i => $status) {
                Order::firstOrCreate(
                    ['order_number' => 'CC-100' . ($i + 1)],
                    [
                        'user_id' => $user->id,
                        'subtotal' => 4500 + ($i * 1200),
                        'discount' => 0,
                        'total' => 4500 + ($i * 1200),
                        'status' => $status,
                        'shipping_address' => 'MG Road, Indore',
                        'created_at' => Carbon::today()->subDays(6 - $i),
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}