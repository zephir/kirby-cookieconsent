<?php

# Use @include_once in case plugin is not installed through zip
# see https://getkirby.com/docs/guide/plugins/plugin-setup-composer#support-for-plugin-installation-without-composer
@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Http\Response;
use Zephir\Cookieconsent\Configuration;

Kirby::plugin('zephir/cookieconsent', [
    'snippets' => [
        'cookieconsentJs' => __DIR__ . '/snippets/cookieconsent_js.php',
        'cookieconsentCss' => __DIR__ . '/snippets/cookieconsent_css.php'
    ],
    'routes' => function ($kirby) {
        return [
            [
                'pattern' => 'cookieconsent.js',
                'language' => '*',
                'action' => function () {
                    return new Response(
                        Configuration::getInitJs(),
                        'application/javascript',
                        200,
                        [
                            'Cache-Control' => 'public, max-age=3600, must-revalidate'
                        ]
                    );
                }
            ]
        ];
    },
    'options' => [
        'cdn' => false,

        // CookieConsent options
        // Root options
        'revision' => 1,
        'root' => 'document.body',
        'autoClearCookies' => true, // Only works when the categories has an autoClear array
        'autoShow' => true,
        'hideFromBots' => true,
        'disablePageInteraction' => false,
        'lazyHtmlGeneration' => true,

        // Block options
        'guiOptions' => [
            'consentModal' => [
                'layout' => 'box',
                'position' => 'bottom right',
                'flipButtons' => false,
                'equalWeightButtons' => true
            ],
            'preferencesModal' => [
                'layout' => 'box',
                // 'position' => 'left',
                'flipButtons' => false,
                'equalWeightButtons' => true
            ]
        ],
        'categories' => [
            'necessary' => [
                'enabled' => true,
                'readOnly' => true
            ],
            'measurement' => [],
            'functionality' => [],
            'experience' => [],
            'marketing' => []
        ],

        // Language options
        'translations' => [
            'de' => require_once(__DIR__ . '/translations/de.php'),
            'en' => require_once(__DIR__ . '/translations/en.php'),
            'fr' => require_once(__DIR__ . '/translations/fr.php'),
            'nl' => require_once(__DIR__ . '/translations/nl.php'),
            'es' => require_once(__DIR__ . '/translations/es.php'),
            'ca' => require_once(__DIR__ . '/translations/ca.php'),
            'pt_PT' => require_once(__DIR__ . '/translations/pt_PT.php')
        ]
    ]
]);