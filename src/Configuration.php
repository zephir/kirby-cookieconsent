<?php

namespace Zephir\Cookieconsent;

class Configuration
{
    public static function getInitJs()
    {
        return "
            CookieConsent.run(" . json_encode(self::get()) . ");
        ";
    }

    public static function get()
    {
        return [
            'categories' => kirby()->option('zephir.cookieconsent.categories'),
            'guiOptions' => kirby()->option('zephir.cookieconsent.guiOptions'),
            'revision' => kirby()->option('zephir.cookieconsent.revision'),
            'root' => kirby()->option('zephir.cookieconsent.root'),
            'autoClearCookies' => kirby()->option('zephir.cookieconsent.autoClearCookies'), // Only works when the categories has an autoClear array
            'autoShow' => kirby()->option('zephir.cookieconsent.autoShow'),
            'hideFromBots' => kirby()->option('zephir.cookieconsent.hideFromBots'),
            'disablePageInteraction' => kirby()->option('zephir.cookieconsent.disablePageInteraction'),
            'lazyHtmlGeneration' => kirby()->option('zephir.cookieconsent.lazyHtmlGeneration'),
            'language' => [
                'default' => 'kirby',
                'autoDetect' => false,
                'rtl' => kirby()->language()->direction() === 'rtl',
                'translations' => [
                    'kirby' => Helpers::getTranslations()
                ]
            ]
        ];
    }

}