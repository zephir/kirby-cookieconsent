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
        $categories = self::getCategories();

        // the resolved translation key (e.g. `de`, `pt_PT`) doubles as the
        // cookieconsent language code, which ends up in the consent cookie
        $language = self::translationKey();

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
                'default' => $language,
                'autoDetect' => false,
                // cookieconsent expects the language code(s) that are rtl, not a boolean
                'rtl' => self::isRtl() ? $language : null,
                'translations' => [
                    $language => self::syncTranslations($categories)
                ]
            ]
        ];
    }

    /**
     * Get the active categories. A category set to `false` is removed
     * completely, so the library never knows about it and "Accept all"
     * cannot store it in the consent cookie.
     */
    private static function getCategories(): array
    {
        $categories = kirby()->option('zephir.cookieconsent.categories', []);
        $categories = array_filter($categories, fn ($category) => $category !== false);

        // An empty array means "category with default settings". A category is
        // an object in the library's config, so encode `[]` as `{}`
        return array_map(
            fn ($category) => $category === [] ? new \stdClass() : $category,
            $categories
        );
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

            // if the linked category is not active, remove translation section
            if (array_key_exists($linkedCategory, $categories) === false) {
                unset($sections[$key]);
            }
        }

        $translations['preferencesModal']['sections'] = array_values($sections);

        return $translations;
    }

    /**
     * Get the plugin translation for the current language.
     *
     * The translation key is resolved in this order:
     * 1. the locale of the current Kirby language (e.g. `pt_PT`; `pt_PT.UTF-8` and `pt-PT` match too)
     * 2. the code of the current Kirby language (e.g. `pt`)
     * 3. a translation for the same base language (e.g. `pt` or `pt_BR` → `pt_PT`)
     * 4. the `language.locale` option, then `en`, then the first registered translation
     *
     * On single-language sites the `language.locale` option takes the place of 1. and 2.
     */
    private static function getTranslations(): array
    {
        return self::translations()[self::translationKey()] ?? [];
    }

    private static function translations(): array
    {
        return array_filter(
            kirby()->option('zephir.cookieconsent.translations', []),
            'is_array'
        );
    }

    private static function translationKey(): string
    {
        return (string)(self::resolveTranslationKey(array_keys(self::translations())) ?? 'en');
    }

    private static function resolveTranslationKey(array $keys): int|string|null
    {
        $optionLocale = kirby()->option('zephir.cookieconsent.language.locale');
        $language = kirby()->language();

        if ($language !== null) {
            $candidates = [...self::languageLocales($language), $language->code()];
            $fallbacks = [$optionLocale, 'en'];
        } else {
            $candidates = [$optionLocale ?? 'en'];
            $fallbacks = ['en'];
        }

        $candidates = array_filter($candidates, 'is_string');
        $fallbacks = array_filter($fallbacks, 'is_string');

        return self::matchKey($keys, $candidates)
            ?? self::matchBaseLanguage($keys, $candidates)
            ?? self::matchKey($keys, $fallbacks)
            ?? $keys[0] ?? null;
    }

    /**
     * All locale strings of a Kirby language, LC_ALL first
     */
    private static function languageLocales(\Kirby\Cms\Language $language): array
    {
        $locales = $language->locale();
        $all = $locales[LC_ALL] ?? null;
        unset($locales[LC_ALL]);

        $flat = [];
        array_walk_recursive($locales, function ($locale) use (&$flat) {
            $flat[] = $locale;
        });

        return [...(array)$all, ...$flat];
    }

    /**
     * Find the first candidate that matches a translation key,
     * exactly or after normalisation (`pt-PT`, `pt_PT.UTF-8`, `pt_pt` → `pt_PT`)
     */
    private static function matchKey(array $keys, array $candidates): int|string|null
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $keys, true)) {
                return $candidate;
            }

            foreach ($keys as $key) {
                if (self::normalizeLocale((string)$key) === self::normalizeLocale($candidate)) {
                    return $key;
                }
            }
        }

        return null;
    }

    /**
     * Find a translation for the same base language as one of the candidates,
     * e.g. `pt` or `pt_BR` → `pt_PT`, `de_CH` → `de`
     */
    private static function matchBaseLanguage(array $keys, array $candidates): int|string|null
    {
        foreach ($candidates as $candidate) {
            $base = self::baseLanguage($candidate);

            foreach ($keys as $key) {
                if (self::baseLanguage((string)$key) === $base) {
                    return $key;
                }
            }
        }

        return null;
    }

    private static function normalizeLocale(string $locale): string
    {
        // strip the codeset and modifier, e.g. `pt_PT.UTF-8@euro` → `pt_PT`
        $locale = preg_replace('/[.@].*$/', '', $locale);

        return strtolower(str_replace('-', '_', trim($locale)));
    }

    private static function baseLanguage(string $locale): string
    {
        return explode('_', self::normalizeLocale($locale))[0];
    }

    private static function isRtl(): bool
    {
        $direction = kirby()->language()?->direction()
            ?? kirby()->option('zephir.cookieconsent.language.direction', 'ltr');

        return $direction === 'rtl';
    }

}
