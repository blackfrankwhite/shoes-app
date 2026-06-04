<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Factory Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ],
        );

        User::query()->firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ],
        );

        $categories = collect([
            ['name' => 'Sneakers', 'description' => 'Everyday factory-made sneakers for Tbilisi streets.'],
            ['name' => 'Boots', 'description' => 'Durable leather boots for colder seasons.'],
            ['name' => 'Loafers', 'description' => 'Clean silhouettes for office and formal wear.'],
            ['name' => 'Kids Shoes', 'description' => 'Comfortable small sizes with flexible soles.'],
        ])->mapWithKeys(function (array $data): array {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_active' => true,
                ],
            );

            return [$category->slug => $category];
        });

        $sizes = collect(range(28, 46))->mapWithKeys(function (int $value): array {
            $size = Size::query()->updateOrCreate(
                ['value' => (string) $value],
                [
                    'label' => 'EU '.$value,
                    'sort_order' => $value,
                    'is_active' => true,
                ],
            );

            return [$value => $size];
        });

        $colors = collect([
            ['Black', '#111111'],
            ['White', '#F5F5F5'],
            ['Graphite', '#5B5F64'],
            ['Tan', '#B9855B'],
            ['Burgundy', '#6F1D2D'],
            ['Olive', '#6B7447'],
        ])->mapWithKeys(function (array $data): array {
            [$name, $hex] = $data;
            $color = Color::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'hex_code' => $hex,
                    'is_active' => true,
                ],
            );

            return [$color->slug => $color];
        });

        $products = [
            [
                'category' => 'sneakers',
                'name' => 'Saburtalo Low Runner',
                'sex' => 'unisex',
                'price' => 185,
                'sku' => 'TSF-SBR-001',
                'stock' => 28,
                'featured' => true,
                'sizes' => range(36, 45),
                'colors' => ['white', 'graphite'],
                'images' => ['shoe-1.png', 'shoe-2.png'],
            ],
            [
                'category' => 'boots',
                'name' => 'Mtatsminda Work Boot',
                'sex' => 'men',
                'price' => 260,
                'sku' => 'TSF-MTB-014',
                'stock' => 16,
                'featured' => true,
                'sizes' => range(39, 46),
                'colors' => ['black', 'tan'],
                'images' => ['shoe-3.png', 'shoe-4.png'],
            ],
            [
                'category' => 'loafers',
                'name' => 'Sololaki Leather Loafer',
                'sex' => 'women',
                'price' => 220,
                'sku' => 'TSF-SLL-024',
                'stock' => 12,
                'featured' => true,
                'sizes' => range(35, 41),
                'colors' => ['burgundy', 'black'],
                'images' => ['shoe-5.png', 'shoe-6.png'],
            ],
            [
                'category' => 'kids-shoes',
                'name' => 'Vake Mini Trainer',
                'sex' => 'kids',
                'price' => 115,
                'sku' => 'TSF-VMT-030',
                'stock' => 34,
                'featured' => true,
                'sizes' => range(28, 34),
                'colors' => ['white', 'olive'],
                'images' => ['shoe-7.png', 'shoe-8.png'],
            ],
            [
                'category' => 'sneakers',
                'name' => 'Rustaveli Court Sneaker',
                'sex' => 'men',
                'price' => 195,
                'sku' => 'TSF-RCS-041',
                'stock' => 22,
                'featured' => false,
                'sizes' => range(40, 46),
                'colors' => ['black', 'white'],
                'images' => ['shoe-2.png', 'shoe-6.png'],
            ],
            [
                'category' => 'boots',
                'name' => 'Didube Chelsea Boot',
                'sex' => 'women',
                'price' => 248,
                'sku' => 'TSF-DCB-051',
                'stock' => 10,
                'featured' => false,
                'sizes' => range(36, 42),
                'colors' => ['tan', 'burgundy'],
                'images' => ['shoe-4.png', 'shoe-5.png'],
            ],
        ];

        $createdProducts = collect($products)->map(function (array $data) use ($categories, $colors, $sizes): Product {
            $product = Product::query()->updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id' => $categories[$data['category']]->id,
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']),
                    'sex' => $data['sex'],
                    'price' => $data['price'],
                    'description' => 'Made in Tbilisi with a practical last, replaceable laces, and materials selected for daily local wear. Reserve online and pay at the factory after inspection.',
                    'stock_quantity' => $data['stock'],
                    'featured' => $data['featured'],
                    'is_active' => true,
                ],
            );

            $product->sizes()->sync(collect($data['sizes'])->map(fn (int $size) => $sizes[$size]->id));
            $product->colors()->sync(collect($data['colors'])->map(fn (string $color) => $colors[$color]->id));
            $product->images()->delete();

            foreach ($data['images'] as $index => $image) {
                $product->images()->create([
                    'path' => '/images/placeholders/'.$image,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                    'is_main' => $index === 0,
                ]);
            }

            return $product;
        });

        Inquiry::query()->create([
            'product_id' => $createdProducts->first()->id,
            'size_id' => $createdProducts->first()->sizes()->first()->id,
            'color_id' => $createdProducts->first()->colors()->first()->id,
            'name' => 'Nino Beridze',
            'phone' => '+995 599 12 34 56',
            'email' => 'nino@example.com',
            'quantity' => 1,
            'comment' => 'Can I inspect this pair tomorrow afternoon?',
            'status' => 'new',
        ]);
    }
}
