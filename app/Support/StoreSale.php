<?php

namespace App\Support;

use App\Models\SiteSetting;

class StoreSale
{
    public function settings(): array
    {
        $request = request();
        if (! $request->attributes->has('store_sale')) {
            $settings = json_decode(SiteSetting::value('store_sale') ?? '{}', true) ?: [];
            $request->attributes->set('store_sale', [
                'enabled' => (bool) ($settings['enabled'] ?? false),
                'percent' => (int) ($settings['percent'] ?? 0),
            ]);
        }

        return $request->attributes->get('store_sale');
    }

    public function percent(): int
    {
        $settings = $this->settings();

        return $settings['enabled'] && $settings['percent'] >= 1 && $settings['percent'] <= 100
            ? $settings['percent'] : 0;
    }
}
