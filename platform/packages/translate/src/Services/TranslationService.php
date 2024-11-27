<?php

namespace Workable\Translate\Services;

use Illuminate\Support\Facades\DB;

class TranslationService
{
    protected $translations;

    public function __construct()
    {
        $this->loadTranslations();
    }

    /**
     * Load all translations from the database.
     */
    protected function loadTranslations()
    {
        $this->translations = DB::table('translates')
            ->get()
            ->groupBy('language_code')
            ->map(function ($group) {
                return $group->pluck('translation', 'key')->toArray();
            })
            ->toArray();
    }

    /**
     * Get a translation for the given key and language.
     *
     * @param string $key
     * @param string|null $language
     * @return string|null
     */
    public function get(string $key, ?string $language = null): ?string
    {
        $language = $language ?? app()->getLocale();
        return $this->translations[$language][$key] ?? null;
    }

    /**
     * Reload translations from the database.
     */
    public function reload()
    {
        $this->loadTranslations();
    }
}
