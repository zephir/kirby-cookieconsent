<?php

namespace Zephir\Cookieconsent;

class Helpers
{
    public static function getTranslations()
    {
        $translations = kirby()->option('zephir.cookieconsent.translations');
        $locale = kirby()->language()->code();

        if (empty($translations[$locale])) {
            return $translations[array_key_first($translations)];
        }

        return $translations[$locale];
    }

}