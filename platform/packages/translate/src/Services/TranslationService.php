<?php

namespace Workable\Translate\Services;

use Illuminate\Support\Facades\DB;
use Workable\Translate\Models\Translate;

class TranslationService
{
    protected $translations = [];

    public function __construct()
    {
        $this->loadTranslations();
    }

    /**
     * Load all translations from the database.
     */
    protected function loadTranslations()
    {
        $translation = 'translation_' . app()->getLocale();
        if ((new Translate())->checkFieldable($translation)) {
            $this->translations = DB::table('translates')
                ->select('translation_' . app()->getLocale(), 'key')
                ->pluck('translation_' . app()->getLocale(), 'key')->toArray();
        }
    }

    /**
     * Get a translation for the given key.
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->translations[$key] ?? $key;
    }

    /**
     * Reload translations from the database.
     */
    public function reload()
    {
        $this->loadTranslations();
    }
}
