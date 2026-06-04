<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
        });

        $categoryTranslations = [
            'sneakers' => [
                'name_translations' => ['ka' => 'კედები', 'ru' => 'Кроссовки'],
                'description_translations' => [
                    'ka' => 'ყოველდღიური ფაბრიკული კედები თბილისის ქუჩებისთვის.',
                    'ru' => 'Повседневные фабричные кроссовки для улиц Тбилиси.',
                ],
            ],
            'boots' => [
                'name_translations' => ['ka' => 'ჩექმები', 'ru' => 'Ботинки'],
                'description_translations' => [
                    'ka' => 'გამძლე ტყავის ჩექმები ცივი სეზონისთვის.',
                    'ru' => 'Прочные кожаные ботинки для холодного сезона.',
                ],
            ],
            'loafers' => [
                'name_translations' => ['ka' => 'ლოფერები', 'ru' => 'Лоферы'],
                'description_translations' => [
                    'ka' => 'მინიმალისტური მოდელები ოფისისა და ოფიციალური სტილისთვის.',
                    'ru' => 'Лаконичные модели для офиса и формального стиля.',
                ],
            ],
            'kids-shoes' => [
                'name_translations' => ['ka' => 'ბავშვის ფეხსაცმელი', 'ru' => 'Детская обувь'],
                'description_translations' => [
                    'ka' => 'კომფორტული პატარა ზომები მოქნილი ძირით.',
                    'ru' => 'Удобные маленькие размеры с гибкой подошвой.',
                ],
            ],
        ];

        foreach ($categoryTranslations as $slug => $translations) {
            DB::table('categories')->where('slug', $slug)->update([
                'name_translations' => json_encode($translations['name_translations'], JSON_UNESCAPED_UNICODE),
                'description_translations' => json_encode($translations['description_translations'], JSON_UNESCAPED_UNICODE),
            ]);
        }

        $colorTranslations = [
            'black' => ['ka' => 'შავი', 'ru' => 'Черный'],
            'white' => ['ka' => 'თეთრი', 'ru' => 'Белый'],
            'graphite' => ['ka' => 'გრაფიტი', 'ru' => 'Графитовый'],
            'tan' => ['ka' => 'ღია ყავისფერი', 'ru' => 'Рыжевато-коричневый'],
            'burgundy' => ['ka' => 'ბორდო', 'ru' => 'Бордовый'],
            'olive' => ['ka' => 'ზეთისხილისფერი', 'ru' => 'Оливковый'],
        ];

        foreach ($colorTranslations as $slug => $translations) {
            DB::table('colors')->where('slug', $slug)->update([
                'name_translations' => json_encode($translations, JSON_UNESCAPED_UNICODE),
            ]);
        }

        $descriptionTranslations = [
            'ka' => 'დამზადებულია თბილისში პრაქტიკული კალაპოტით, შესაცვლელი თასმებით და ყოველდღიური ადგილობრივი გამოყენებისთვის შერჩეული მასალებით. დაჯავშნეთ ონლაინ და გადაიხადეთ ფაბრიკაში დათვალიერების შემდეგ.',
            'ru' => 'Сделано в Тбилиси с практичной колодкой, сменными шнурками и материалами для ежедневной носки. Бронируйте онлайн и оплачивайте на фабрике после осмотра.',
        ];

        $productTranslations = [
            'TSF-SBR-001' => ['ka' => 'საბურთალოს დაბალი რანერი', 'ru' => 'Низкие раннеры Сабуртало'],
            'TSF-MTB-014' => ['ka' => 'მთაწმინდის სამუშაო ჩექმა', 'ru' => 'Рабочие ботинки Мтацминда'],
            'TSF-SLL-024' => ['ka' => 'სოლოლაკის ტყავის ლოფერი', 'ru' => 'Кожаные лоферы Сололаки'],
            'TSF-VMT-030' => ['ka' => 'ვაკის საბავშვო ტრენერი', 'ru' => 'Детские тренеры Ваке'],
            'TSF-RCS-041' => ['ka' => 'რუსთაველის კორტის კედი', 'ru' => 'Кортовые кроссовки Руставели'],
            'TSF-DCB-051' => ['ka' => 'დიდუბის ჩელსი ჩექმა', 'ru' => 'Челси ботинки Дидубе'],
        ];

        foreach ($productTranslations as $sku => $translations) {
            DB::table('products')->where('sku', $sku)->update([
                'name_translations' => json_encode($translations, JSON_UNESCAPED_UNICODE),
                'description_translations' => json_encode($descriptionTranslations, JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_translations', 'description_translations']);
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('name_translations');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_translations', 'description_translations']);
        });
    }
};
