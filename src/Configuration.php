<?php

namespace Zephir\Cookieconsent;

class Configuration
{
    public static function getInitJs()
    {
        return "CookieConsent.run(" . json_encode(self::get()) . ");";
    }

    public static function get()
    {
        $categories = kirby()->option('zephir.cookieconsent.categories');

        return [
            'categories' => $categories,
            'guiOptions' => kirby()->option('zephir.cookieconsent.guiOptions'),
            'revision' => kirby()->option('zephir.cookieconsent.revision'),
            'root' => kirby()->option('zephir.cookieconsent.root'),
            'autoClearCookies' => kirby()->option('zephir.cookieconsent.autoClearCookies'), // Only works when the categories have an autoClear array
            'autoShow' => kirby()->option('zephir.cookieconsent.autoShow'),
            'hideFromBots' => kirby()->option('zephir.cookieconsent.hideFromBots'),
            'disablePageInteraction' => kirby()->option('zephir.cookieconsent.disablePageInteraction'),
            'lazyHtmlGeneration' => kirby()->option('zephir.cookieconsent.lazyHtmlGeneration'),
            'language' => [
                'default' => 'kirby',
                'autoDetect' => false,
                'rtl' => self::isRtl(),
                'translations' => [
                    'kirby' => self::syncTranslations($categories)
                ]
            ]
        ];
    }

    /**
     * Get translations filtered according to active categories only
     */
    private static function syncTranslations(array $categories): array
    {
        $translations = self::getTranslations();
        $sections = $translations['preferencesModal']['sections'];

        foreach ($sections as $key => $section) {
            $linkedCategory = $section['linkedCategory'] ?? null;

            // if section has no linked category, ignore and go to next
            if ($linkedCategory === null) {
                continue;
            }

            // if category exists and is false, remove translation section
            if (isset($categories[$linkedCategory]) && $categories[$linkedCategory] === false) {
                unset($sections[$key]);
            }
        }

        $translations['preferencesModal']['sections'] = array_values($sections);

        return $translations;
    }

    private static function getTranslations()
    {
        $translations = kirby()->option('zephir.cookieconsent.translations');
        $locale = kirby()->option('zephir.cookieconsent.language.locale', 'en');

        if (option('languages')) {
            $locale = kirby()->language()->code();
        }

        if (empty($translations[$locale])) {
            return $translations[array_key_first($translations)];
        }

        return $translations[$locale];
    }

    private static function isRtl()
    {
        $direction = option('zephir.cookieconsent.language.direction', 'ltr');

        if (option('languages')) {
            $direction = kirby()->language()->direction();
        }

        return $direction === 'rtl';
    }

}
