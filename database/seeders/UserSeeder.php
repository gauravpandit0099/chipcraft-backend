<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /* Seed single-vendor admin account */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@chipcraft.com'],
            ['name' => 'Chipcraft Admin', 'password' => 'admin123', 'role' => 'admin']
        );
    }
}
