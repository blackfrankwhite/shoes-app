<?php

namespace App\Models\Concerns;

trait HasTranslations
{
    public function translated(string $attribute, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $translations = $this->getAttribute($attribute.'_translations') ?? [];
        $translation = $translations[$locale] ?? null;

        return filled($translation) ? $translation : $this->getAttribute($attribute);
    }
}
