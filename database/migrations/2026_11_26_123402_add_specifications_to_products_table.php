<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /* add specifications json column */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->longText('specifications')->nullable()->after('description');
        });
    }

    /* reverse specifications column */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('specifications');
        });
    }
};