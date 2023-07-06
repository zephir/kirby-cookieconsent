<?php

namespace Zephir\Cookieconsent;

class OptionBlocks
{
    public static function getActive()
    {
        $blocks = self::getAll();
        $activeBlocks = kirby()->option('zephir.cookieconsent.activeBlocks');

        return array_values(
            array_filter(
                $blocks,
                function ($name) use ($activeBlocks) {
                    return isset($activeBlocks[$name]) && $activeBlocks[$name] === true;
                },
                ARRAY_FILTER_USE_KEY
            )
        );
    }

    public static function getAll()
    {
        return [
            'necessary' => self::getNecessary(),
            'functionality' => self::getFunctionality(),
            'experience' => self::getExperience(),
            'measurement' => self::getMeasurement(),
            'marketing' => self::getMarketing()
        ];
    }

    public static function getNecessary()
    {
        return [
            'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.necessary_title'),
            'description' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.necessary_description'),
            'toggle' => [
                'value' => 'necessary',
                'enabled' => true,
                'readonly' => true
            ]
        ];
    }

    public static function getFunctionality()
    {
        return [
            'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.functionality_title'),
            'description' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.functionality_description'),
            'toggle' => [
                'value' => 'functionality',
                'enabled' => false,
                'readonly' => false
            ]
        ];
    }

    public static function getExperience()
    {
        return [
            'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.experience_title'),
            'description' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.experience_description'),
            'toggle' => [
                'value' => 'experience',
                'enabled' => false,
                'readonly' => false
            ]
        ];
    }

    public static function getMeasurement()
    {
        return [
            'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.measurement_title'),
            'description' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.measurement_description'),
            'toggle' => [
                'value' => 'measurement',
                'enabled' => false,
                'readonly' => false
            ]
        ];
    }

    public static function getMarketing()
    {
        return [
            'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.marketing_title'),
            'description' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.marketing_description'),
            'toggle' => [
                'value' => 'marketing',
                'enabled' => false,
                'readonly' => false
            ]
        ];
    }

}