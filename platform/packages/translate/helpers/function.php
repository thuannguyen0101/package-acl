<?php
if (!function_exists('__trans')) {
    /**
     * Get a translation for the given key.
     *
     * @param string $key
     * @return string|null
     */
    function __trans(string $key): ?string
    {
        return app(Workable\Translate\Services\TranslationService::class)->get($key);
    }
}

