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
            [
                'slug' => 'sneakers',
                'name' => 'კედები',
                'name_translations' => ['en' => 'Sneakers', 'ru' => 'Кроссовки'],
                'description' => 'ყოველდღიური ფაბრიკული კედები თბილისის ქუჩებისთვის.',
                'description_translations' => [
                    'en' => 'Everyday factory-made sneakers for Tbilisi streets.',
                    'ru' => 'Повседневные фабричные кроссовки для улиц Тбилиси.',
                ],
            ],
            [
                'slug' => 'boots',
                'name' => 'ჩექმები',
                'name_translations' => ['en' => 'Boots', 'ru' => 'Ботинки'],
                'description' => 'გამძლე ტყავის ჩექმები ცივი სეზონისთვის.',
                'description_translations' => [
                    'en' => 'Durable leather boots for colder seasons.',
                    'ru' => 'Прочные кожаные ботинки для холодного сезона.',
                ],
            ],
            [
                'slug' => 'loafers',
                'name' => 'ლოფერები',
                'name_translations' => ['en' => 'Loafers', 'ru' => 'Лоферы'],
                'description' => 'მინიმალისტური მოდელები ოფისისა და ოფიციალური სტილისთვის.',
                'description_translations' => [
                    'en' => 'Clean silhouettes for office and formal wear.',
                    'ru' => 'Лаконичные модели для офиса и формального стиля.',
                ],
            ],
            [
                'slug' => 'kids-shoes',
                'name' => 'ბავშვის ფეხსაცმელი',
                'name_translations' => ['en' => 'Kids Shoes', 'ru' => 'Детская обувь'],
                'description' => 'კომფორტული პატარა ზომები მოქნილი ძირით.',
                'description_translations' => [
                    'en' => 'Comfortable small sizes with flexible soles.',
                    'ru' => 'Удобные маленькие размеры с гибкой подошвой.',
                ],
            ],
        ])->mapWithKeys(function (array $data): array {
            $category = Category::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'name_translations' => $data['name_translations'],
                    'description' => $data['description'],
                    'description_translations' => $data['description_translations'],
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
            ['black', 'შავი', '#111111', ['en' => 'Black', 'ru' => 'Черный']],
            ['white', 'თეთრი', '#F5F5F5', ['en' => 'White', 'ru' => 'Белый']],
            ['graphite', 'გრაფიტი', '#5B5F64', ['en' => 'Graphite', 'ru' => 'Графитовый']],
            ['tan', 'ღია ყავისფერი', '#B9855B', ['en' => 'Tan', 'ru' => 'Рыжевато-коричневый']],
            ['burgundy', 'ბორდო', '#6F1D2D', ['en' => 'Burgundy', 'ru' => 'Бордовый']],
            ['olive', 'ზეთისხილისფერი', '#6B7447', ['en' => 'Olive', 'ru' => 'Оливковый']],
        ])->mapWithKeys(function (array $data): array {
            [$slug, $name, $hex, $translations] = $data;
            $color = Color::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'name_translations' => $translations,
                    'hex_code' => $hex,
                    'is_active' => true,
                ],
            );

            return [$color->slug => $color];
        });

        $descriptionTranslations = [
            'en' => 'Made in Tbilisi with a practical last, replaceable laces, and materials selected for daily local wear. Reserve online and pay at the factory after inspection.',
            'ru' => 'Сделано в Тбилиси с практичной колодкой, сменными шнурками и материалами для ежедневной носки. Бронируйте онлайн и оплачивайте на фабрике после осмотра.',
        ];

        $products = [
            [
                'category' => 'sneakers',
                'name' => 'საბურთალოს დაბალი რანერი',
                'name_translations' => ['en' => 'Saburtalo Low Runner', 'ru' => 'Низкие раннеры Сабуртало'],
                'slug' => 'saburtalo-low-runner',
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
                'name' => 'მთაწმინდის სამუშაო ჩექმა',
                'name_translations' => ['en' => 'Mtatsminda Work Boot', 'ru' => 'Рабочие ботинки Мтацминда'],
                'slug' => 'mtatsminda-work-boot',
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
                'name' => 'სოლოლაკის ტყავის ლოფერი',
                'name_translations' => ['en' => 'Sololaki Leather Loafer', 'ru' => 'Кожаные лоферы Сололаки'],
                'slug' => 'sololaki-leather-loafer',
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
                'name' => 'ვაკის საბავშვო ტრენერი',
                'name_translations' => ['en' => 'Vake Mini Trainer', 'ru' => 'Детские тренеры Ваке'],
                'slug' => 'vake-mini-trainer',
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
                'name' => 'რუსთაველის კორტის კედი',
                'name_translations' => ['en' => 'Rustaveli Court Sneaker', 'ru' => 'Кортовые кроссовки Руставели'],
                'slug' => 'rustaveli-court-sneaker',
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
                'name' => 'დიდუბის ჩელსი ჩექმა',
                'name_translations' => ['en' => 'Didube Chelsea Boot', 'ru' => 'Челси ботинки Дидубе'],
                'slug' => 'didube-chelsea-boot',
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

        $createdProducts = collect($products)->map(function (array $data) use ($categories, $colors, $descriptionTranslations, $sizes): Product {
            $product = Product::query()->updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id' => $categories[$data['category']]->id,
                    'name' => $data['name'],
                    'name_translations' => $data['name_translations'],
                    'slug' => $data['slug'],
                    'sex' => $data['sex'],
                    'price' => $data['price'],
                    'description' => 'დამზადებულია თბილისში პრაქტიკული კალაპოტით, შესაცვლელი თასმებით და ყოველდღიური ადგილობრივი გამოყენებისთვის შერჩეული მასალებით. დაჯავშნეთ ონლაინ და გადაიხადეთ ფაბრიკაში დათვალიერების შემდეგ.',
                    'description_translations' => $descriptionTranslations,
                    'stock_quantity' => $data['stock'],
                    'featured' => $data['featured'],
                    'is_active' => true,
                ],
            );

            $product->sizes()->sync(collect($data['sizes'])->map(fn (int $size) => $sizes[$size]->id));
            $product->colors()->sync(collect($data['colors'])->mapWithKeys(fn (string $color): array => [
                $colors[$color]->id => ['sku' => $data['sku'].'-'.str($color)->upper()],
            ]));
            $product->images()->delete();

            foreach ($data['images'] as $index => $image) {
                $imageColorSlug = $data['colors'][$index % count($data['colors'])];

                $product->images()->create([
                    'color_id' => $colors[$imageColorSlug]->id,
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
