<?php

namespace Zephir\Cookieconsent;

use InvalidArgumentException;

class Configuration
{
    /**
     * Root callbacks of CookieConsent, passed through when set.
     * See https://cookieconsent.orestbida.com/reference/configuration-reference.html#callbacks
     */
    public const CALLBACKS = [
        'onFirstConsent',
        'onConsent',
        'onChange',
        'onModalReady',
        'onModalShow',
        'onModalHide',
    ];

    public static function getInitJs()
    {
        return "CookieConsent.run(" . Js::encode(self::get()) . ");";
    }

    /**
     * JSON encodes a value so it is safe to embed in JavaScript, also inline
     * in HTML: `<`, `>`, `&` and `'` are escaped as unicode sequences.
     */
    public static function encode(mixed $value): string
    {
        return json_encode(
            $value,
            JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS
        );
    }

    public static function get()
    {
        $categories = kirby()->option('zephir.cookieconsent.categories');

        return [
            ...self::callbacks(),
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
     * The root callbacks that are set, each a `Js::raw()` function.
     */
    private static function callbacks(): array
    {
        $callbacks = [];

        foreach (self::CALLBACKS as $name) {
            $callback = kirby()->option('zephir.cookieconsent.' . $name);

            if ($callback === null) {
                continue;
            }

            if (!$callback instanceof Js) {
                throw new InvalidArgumentException(sprintf(
                    'The option zephir.cookieconsent.%s must be a JavaScript function wrapped in Zephir\\Cookieconsent\\Js::raw().',
                    $name
                ));
            }

            $callbacks[$name] = $callback;
        }

        return $callbacks;
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
