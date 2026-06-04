<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->change();
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->foreignId('color_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->index(['product_id', 'color_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'color_id', 'sort_order']);
            $table->dropConstrainedForeignId('color_id');
        });

        foreach (DB::table('products')->whereNull('sku')->select('id')->cursor() as $product) {
            DB::table('products')->where('id', $product->id)->update(['sku' => 'PRODUCT-'.$product->id]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable(false)->change();
        });
    }
};
