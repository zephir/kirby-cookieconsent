<?php

namespace Zephir\Cookieconsent;

class Options
{
    /**
     * @param string $type
     * @return array
     */
    public static function get($type)
    {
        $allOptions = [
            'simple' => self::getSimpleOptions(),
            'customizable' => self::getCustomizableOptions()
        ];
        $options = [];

        if ($type) {
            $options = isset($allOptions[$type]) ? $allOptions[$type] : $allOptions['simple'];
        }

        return array_merge_recursive(
            $options,
            kirby()->option('zephir.cookieconsent.extend')
        );
    }

    private static function getSimpleOptions()
    {
        return array_merge_recursive(
            self::getBaseOptions(),
            [
                'languages' => [
                    'kirby' => [
                        'consent_modal' => [
                            'secondary_btn' => [
                                'text' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.reject'),
                                'role' => 'accept_necessary'
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

    private static function getCustomizableOptions()
    {
        return self::getBaseOptions();
    }

    private static function getBaseOptions()
    {
        return [
            'current_lang' => 'kirby',
            'page_scripts' => true,
            'languages' => [
                'kirby' => [
                    'consent_modal' => [
                        'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.consent_title'),
                        'description' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.consent_description'),
                        'primary_btn' => [
                            'text' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.accept'),
                            'role' => 'accept_all'
                        ],
                        'secondary_btn' => [
                            'text' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.settings'),
                            'role' => 'settings'
                        ]
                    ],
                    'settings_modal' => [
                        'title' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.settings_title'),
                        'save_settings_btn' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.save_settings'),
                        'accept_all_btn' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.accept_all'),
                        'reject_all_btn' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.reject_all'),
                        'close_btn_label' => Helpers::getCookieconsentTranslation('zephir.cookieconsent.close'),
                        'cookie_table_headers' => [
                            ['col1' => 'Name']
                        ],
                        'blocks' => OptionBlocks::getActive()
                    ]
                ]
            ]
        ];
    }

}