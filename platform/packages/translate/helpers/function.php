<?php
if (!function_exists('__trans')) {
    /**
     * Get a translation for the given key.
     *
     * @param string $key
     * @param string|null $language
     * @return string|null
     */
    function __trans(string $key, ?string $language = null): ?string
    {
        return app(Workable\Translate\Services\TranslationService::class)->get($key, $language);
    }
}

