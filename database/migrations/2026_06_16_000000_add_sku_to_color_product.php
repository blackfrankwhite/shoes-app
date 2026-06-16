<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('color_product', function (Blueprint $table): void {
            $table->string('sku')->nullable()->after('color_id');
            $table->unique('sku');
        });

        DB::table('color_product')
            ->join('products', 'color_product.product_id', '=', 'products.id')
            ->join('colors', 'color_product.color_id', '=', 'colors.id')
            ->select('color_product.product_id', 'color_product.color_id', 'products.sku as product_sku', 'colors.slug as color_slug')
            ->orderBy('color_product.product_id')
            ->orderBy('color_product.color_id')
            ->get()
            ->each(function (object $row): void {
                $baseSku = $row->product_sku ?: 'PRODUCT-'.$row->product_id;
                $colorCode = Str::upper(Str::slug((string) $row->color_slug, '-'));

                DB::table('color_product')
                    ->where('product_id', $row->product_id)
                    ->where('color_id', $row->color_id)
                    ->update(['sku' => "{$baseSku}-{$colorCode}"]);
            });

        Schema::table('color_product', function (Blueprint $table): void {
            $table->string('sku')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('color_product', function (Blueprint $table): void {
            $table->dropUnique(['sku']);
            $table->dropColumn('sku');
        });
    }
};
