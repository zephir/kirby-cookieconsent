# Kirby Cookieconsent plugin

![cover](docs/cover.png)

A plugin to implement [cookieconsent](https://github.com/orestbida/cookieconsent) in Kirby.

- Uses the open source cookieconsent library
- Provides default translations for 5 cookie categories
- Multilingual support (EN/DE/FR/NL/ES/CA/PT-PT)
- Customizable

## Table of Contents

- [Kirby Cookieconsent plugin](#kirby-cookieconsent-plugin)
  - [Table of Contents](#table-of-contents)
  - [Requirements](#requirements)
  - [1. Installation](#1-installation)
    - [1.1 Composer](#11-composer)
    - [1.2 Download](#12-download)
    - [1.3 Git submodule](#13-git-submodule)
  - [2. Setup](#2-setup)
  - [3. Options](#3-options)
    - [3.1 Available options](#31-available-options)
    - [3.2 Defaults](#32-defaults)
    - [3.3 Predefined cookie categories](#33-predefined-cookie-categories)
    - [3.4 Removing a category](#34-removing-a-category)
  - [4. Language](#4-language)
  - [5. Translations](#5-translations)
    - [5.1 Overriding specific translations](#51-overriding-specific-translations)
  - [6. Events](#6-events)
  - [7. JavaScript in the config: callbacks, services and regexes](#7-javascript-in-the-config-callbacks-services-and-regexes)
  - [License](#license)
  - [Credits](#credits)


## Requirements

- Kirby 4, 5 or 6
- PHP 8.1 or newer (and the PHP version your Kirby release requires)

Version 4 of this plugin drops support for Kirby 3. If you are still on Kirby 3, stay on version 3.x of this plugin.

## 1. Installation

The recommended way of installing is by using Composer.

### 1.1 Composer

```
composer require zephir/kirby-cookieconsent
```

### 1.2 Download

Download and copy this repository to `/site/plugins/kirby-cookieconsent`.

### 1.3 Git submodule

```
git submodule add https://github.com/zephir/kirby-cookieconsent.git site/plugins/kirby-cookieconsent
```

## 2. Setup

Add `snippet('cookieconsentCss')` to your header and `snippet('cookieconsentJs')` to your footer.

## 3. Options

### 3.1 Available options

| Option | Type | Default | Description |
| --- | --- | --- | --- |
| root | string | `"document.body"` | [Root (parent) element where the modal will be appended as a last child.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#root) |
| autoShow | boolean | `true` | [Automatically show the consent modal if consent is not valid.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#autoshow) |
| revision | integer | `1` | [Manages consent revisions; useful if you'd like to ask your users again for consent after a change in your cookie/privacy policy.](https://cookieconsent.orestbida.com/advanced/revision-management.html#enable-revisions) |
| autoClearCookies | boolean | `true` | [Clears cookies when user rejects a specific category. It requires a valid autoClear array.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#autoclearcookies) |
| hideFromBots | boolean | `true` | [Stops the plugin's execution when a bot/crawler is detected, to prevent them from indexing the modal's content.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#hidefrombots) |
| disablePageInteraction | boolean | `true` | [Creates a dark overlay and blocks the page scroll until consent is expressed.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#disablepageinteraction) |
| lazyHtmlGeneration | boolean | `true` | [Delays the generation of the modal's markup until they're about to become visible, to improve the TTI score. You can detect when a modal is ready/created via the onModalReady callback.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#lazyhtmlgeneration) |
| guiOptions | array | [see below](#32-defaults) | [You can extensively customize both the color scheme and the layout, based on your needs.](https://cookieconsent.orestbida.com/advanced/ui-customization.html) |
| categories | array | [see below](#32-defaults) | [Use to define your cookie categories.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#categories) |
| translations | array | [see below](#32-defaults) | An array of translations for each language. |
| language | array | [see below](#32-defaults) | Defines the language and direction for single language installations. |
| cdn | boolean | `false` | Whether to load the cookieconsent assets from jsdelivr.net or use the compiled assets provided with this plugin. |
| onFirstConsent | `Js` | – | [Callback run once, on the user's first consent action.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#onfirstconsent) See [7.](#7-javascript-in-the-config-callbacks-services-and-regexes) |
| onConsent | `Js` | – | [Callback run on the first consent action and on every page load after it.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#onconsent) See [7.](#7-javascript-in-the-config-callbacks-services-and-regexes) |
| onChange | `Js` | – | [Callback run when the user changes their preferences.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#onchange) See [7.](#7-javascript-in-the-config-callbacks-services-and-regexes) |
| onModalReady | `Js` | – | [Callback run when a modal is created and appended to the DOM.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#onmodalready) See [7.](#7-javascript-in-the-config-callbacks-services-and-regexes) |
| onModalShow | `Js` | – | [Callback run when a modal is shown.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#onmodalshow) See [7.](#7-javascript-in-the-config-callbacks-services-and-regexes) |
| onModalHide | `Js` | – | [Callback run when a modal is hidden.](https://cookieconsent.orestbida.com/reference/configuration-reference.html#onmodalhide) See [7.](#7-javascript-in-the-config-callbacks-services-and-regexes) |

### 3.2 Defaults

```php
'zephir.cookieconsent' => [
    'cdn' => false,
    'revision' => 1,
    'root' => 'document.body',
    'autoClearCookies' => true, // Only works when the categories have an autoClear array
    'autoShow' => true,
    'hideFromBots' => true,
    'disablePageInteraction' => false,
    'lazyHtmlGeneration' => true,
    'guiOptions' => [
        'consentModal' => [
            'layout' => 'box',
            'position' => 'bottom right',
            'flipButtons' => false,
            'equalWeightButtons' => true
        ],
        'preferencesModal' => [
            'layout' => 'box',
            // 'position' => 'left', // only relevant with the "bar" layout
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
    'translations' => [
        'de' => require_once(__DIR__ . '/translations/de.php'),
        'en' => require_once(__DIR__ . '/translations/en.php'),
        'fr' => require_once(__DIR__ . '/translations/fr.php')
    ],
    'language' => [
        'locale' => 'en',
        'direction' => 'ltr'
    ]
]
```

### 3.3 Predefined cookie categories

Each cookie category can be used to control certain scripts.
To learn how to manage scripts, head over to the [CookieConsent documentation](https://cookieconsent.orestbida.com/advanced/manage-scripts.html#how-to-manage-scripts).

Categories that are predefined by this plugin:

| Name          | Enabled | Description                                                                                                                          |
| ------------- | ------- | ------------------------------------------------------------------------------------------------------------------------------------ |
| necessary     | ✅      | The necessary cookies, can't be disabled by the user.                                                                                |
| functionality | ✅      | Cookies for basic functionality and communication.                                                                                   |
| experience    | ✅      | Cookies to improve the quality of the user experience and enable the user to interact with external content, networks and platforms. |
| measurement   | ✅      | Cookies that help to measure traffic and analyze behavior.                                                                           |
| marketing     | ✅      | These cookies help us to deliver personalized ads or marketing content to you, and to measure their performance.                     |

> Predefined means that there are translations in all languages for each category.

To enable/disable categories you can use the `categories` option of the plugin.

```php
'zephir.cookieconsent' => [
    'categories' => [
        'necessary' => [
            'enabled' => true, // marks the category as enabled by default
            'readOnly' => true
        ],
        'measurement' => [],
        'functionality' => [],
        'experience' => [],
        'marketing' => []
    ],
]
```

> An empty array defines the category with the default options. You can pass additional options like `enabled` or `readOnly` in the array.
> Learn more about those options in the [CookieConsent Documentation](https://cookieconsent.orestbida.com/reference/configuration-reference.html#categories).

### 3.4 Removing a category

To remove a category you have to set its value to `false`.

```php
'zephir.cookieconsent' => [
    'categories' => [
        'necessary' => [
            'enabled' => true,
            'readOnly' => true
        ],
        'measurement' => [],
        'functionality' => false, // will not be shown
        'experience' => false, // will not be shown
        'marketing' => []
    ],
]
```

A removed category is not passed to CookieConsent at all: its section is hidden in the preferences modal, and neither "Accept all" button stores it in the consent cookie.

Up to version 3 of this plugin, "Accept all" also stored removed categories in the cookie. Visitors who accepted under that behaviour keep these extra categories in their cookie until they consent again. To ask them for consent again, increase the `revision` option.

## 4. Language

The plugin picks one of the registered [translations](#5-translations) for the current page.

**Multi-language sites** (kirby option `languages` set to `true`) need no setup. The translation is chosen from the current Kirby language, in this order:

1. The language's locale, e.g. `pt_PT` for a language with `'locale' => 'pt_PT.UTF-8'`. Codeset and modifier are ignored and `-` matches `_`, so `pt_PT.UTF-8`, `pt-PT` and `pt_pt` all find the `pt_PT` translation.
2. The language's code, e.g. `de`.
3. A translation for the same base language, e.g. a language `pt` or `pt_BR` uses `pt_PT`, and `de_CH` uses `de`.
4. The fallback: the `language.locale` option below if you set it, else `en`, else the first registered translation.

The text direction follows the Kirby language's `direction`.

**Single-language sites** (or `languages` on but no language created yet) use the `language` option. `locale` is matched the same way (steps 1, 3 and 4), so `de_CH` uses `de`.

```php
'zephir.cookieconsent' => [
    'language' => [
        'locale' => 'de', // The translation to use, default is en
        'direction' => 'ltr' // Either ltr or rtl, default is ltr
    ]
]
```

On multi-language sites `language.locale` is only used as the fallback, for languages without a matching translation.

## 5. Translations

The translations are a central part of the plugin. By default, we provide translations for `en`, `de`, `fr`, `nl`, `es`, `ca` and `pt_PT`, and for the categories mentioned in [3.3 Predefined cookie categories](#33-predefined-cookie-categories).
To customise or override the default translations, or to add a language, you will need to use the `translations` option.

Each translation is registered under a key, which is matched against your languages as described in [4. Language](#4-language). Use the language code (`'de' => ...`) or a locale (`'pt_BR' => ...`). When both match, the locale key wins: a language `pt` with locale `pt_PT` uses the `pt_PT` translation even if you also registered `'pt' => ...`, so override a shipped translation under its own key (`pt_PT`).

### 5.1 Overriding specific translations

> For this example we are overriding one string and adding a new category in the sections part of the EN translation.

1. Create a new folder in your project. For this example we call it `cc-translations`.
2. In that folder, create a new file `en.php`.

```php
<?php
// site/cc-translations/en.php

return array_replace_recursive(
    // We assume the plugin was installed as suggested in the installation section of the readme.
    require(__DIR__ . '/../plugins/kirby-cookieconsent/translations/en.php'),
    [
        'consentModal' => [
            'title' => 'Lorem ipsum dolor!', // Override consentModal title
        ],
        "preferencesModal" => [
            "sections" => [
                [ // Add a new category in sections
                    "title" => "Custom scripts",
                    "description" => "Diese Cookies helfen uns, Ihnen personalisierte Werbung oder Marketinginhalte bereitzustellen und deren Leistung zu messen.",
                    "linkedCategory" => "custom-scripts",
                ],
            ]
        ]
    ]
);
```

3. Now you need to require (or include) this file in `config.php`. Because we want to see the new category `custom-scripts` we also need to update the `categories` option.

```php
'zephir.cookieconsent' => [
    'categories' => [
        'custom-scripts' => []
    ],
    'translations' => [
        'en' => require(__DIR__ . '/../cc-translations/en.php')
    ]
]
```

> You can override the whole translation file by copying the default file and adjusting the values.
> You can also require the default translation and manually alter the array by modifying, adding or deleting entries.

## 6. Events

You can find all available events in the [CookieConsent Documentation](https://cookieconsent.orestbida.com/advanced/callbacks-events.html#callbacks-and-events).

> The CookieConsent object is available through the window object (`window.CookieConsent`).

## 7. JavaScript in the config: callbacks, services and regexes

The plugin builds the CookieConsent config in PHP and outputs it as JSON. Most of the config is plain data and works as a normal PHP array, for example a service with a `label` and `cookies` with string names.
Some parts of the [CookieConsent configuration](https://cookieconsent.orestbida.com/reference/configuration-reference.html) have to be JavaScript: the service callbacks `onAccept`/`onReject`, the root callbacks (`onFirstConsent`, `onConsent`, `onChange`, `onModalReady`, `onModalShow`, `onModalHide`) and cookie names given as a regular expression.

For those, wrap the value in `Zephir\Cookieconsent\Js`. You can use it anywhere in the `zephir.cookieconsent` options:

- `Js::raw('function () { ... }')` outputs the JavaScript expression as is, unquoted.
- `Js::regex('^_ga')` outputs a regular expression, the same as `/^_ga/`. The pattern is written without the slashes; flags go in the second argument: `Js::regex('^_ga', 'i')`.

Everything else is still JSON encoded.

```php
<?php
// site/config/config.php

use Zephir\Cookieconsent\Js;

return [
    // `ready` runs after the plugins are loaded, so the Js class is available
    // with every installation method (see the note below)
    'ready' => fn () => [
        'zephir.cookieconsent' => [
            'categories' => [
                'necessary' => [
                    'enabled' => true,
                    'readOnly' => true
                ],
                'measurement' => [
                    // Clears these cookies when the user rejects the category
                    'autoClear' => [
                        'cookies' => [
                            ['name' => Js::regex('^_ga')], // _ga, _ga_XXXXXXXXXX, ...
                            ['name' => '_gid']
                        ]
                    ],
                    'services' => [
                        'ga' => [
                            'label' => 'Google Analytics',
                            'onAccept' => Js::raw('function () {
                                // load Google Analytics here
                            }'),
                            'onReject' => Js::raw('function () {
                                // runs when the user turns off this service after accepting it
                            }'),
                            'cookies' => [
                                ['name' => Js::regex('^_ga')]
                            ]
                        ]
                    ]
                ],
                'functionality' => [],
                'experience' => [],
                'marketing' => []
            ],
            'onConsent' => Js::raw('function ({ cookie }) {
                console.log("Accepted categories:", cookie.categories);
            }')
        ]
    ]
];
```

The service shows up as its own toggle in the preferences modal, below the measurement category.
`onAccept` runs when the service is accepted ("Accept all", or the toggle on) and on every page load after it. `onReject` runs when a service the user had accepted is turned off. With `autoClearCookies` (on by default), the cookies listed in `autoClear` are deleted when the category is rejected, also on "Reject all" at the first visit.

> **Why `ready`?** Kirby reads `config.php` before it loads the plugins. If you installed the plugin with Composer, the `Js` class is autoloaded already and you can use it directly in the `zephir.cookieconsent` option. If you installed it by download or as a git submodule, the class only exists once the plugin is loaded, so put the options that use `Js` in Kirby's [`ready` option](https://getkirby.com/docs/reference/system/options/ready), as above.

`Js::raw()` puts your code into the page unchanged: only use it with your own code, never with content from editors or visitors. The callbacks receive the same arguments as in the [CookieConsent documentation](https://cookieconsent.orestbida.com/advanced/callbacks-events.html#callbacks-and-events).

## License

MIT

## Credits

- [Zephir](https://zephir.ch)
- [Marc Stampfli](https://github.com/themaaarc)
