<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    /* add gallery images json column */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('gallery_images')->nullable()->after('image');
        });
    }

    /* reverse gallery images column */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('gallery_images');
        });
    }
};