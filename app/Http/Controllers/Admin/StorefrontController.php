<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\ProductData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class StorefrontController extends Controller
{
    private const HOME_COVER_KEY = 'home_cover_image_path';

    public function edit(Request $request): Response
    {
        abort_unless($request->user()?->can('access-admin'), 403);

        $coverPath = SiteSetting::value(self::HOME_COVER_KEY);

        return Inertia::render('Admin/Storefront/Edit', [
            'homeCover' => [
                'path' => $coverPath,
                'url' => $coverPath ? ProductData::imageUrl($coverPath) : null,
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->can('access-admin'), 403);

        $validated = $request->validate([
            'home_cover' => ['nullable', 'image', 'max:4096'],
            'remove_home_cover' => ['boolean'],
        ]);

        $coverPath = SiteSetting::value(self::HOME_COVER_KEY);

        if ($request->boolean('remove_home_cover') || $request->hasFile('home_cover')) {
            $this->deleteImageFile($coverPath);
            SiteSetting::set(self::HOME_COVER_KEY, null);
        }

        if ($request->hasFile('home_cover')) {
            SiteSetting::set(self::HOME_COVER_KEY, $request->file('home_cover')->store('storefront'));
        }

        return redirect()->route('admin.storefront.edit')->with('success', __('app.flash.storefront_updated'));
    }

    private function deleteImageFile(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['/', 'http://', 'https://'])) {
            Storage::delete($path);
        }
    }
}
