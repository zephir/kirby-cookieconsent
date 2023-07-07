# Kirby 3 Cookieconsent plugin

![cover](docs/cover.jpg)

A plugin to implement [cookieconsent](https://github.com/orestbida/cookieconsent) in Kirby 3.

- Uses the open source cookieconsent library
- Provides two default configurations to get you started quickly
- Provides several "blocks" for different cookies
- Multilingual support
- Fully customizable

> The plugin needs Kirby 3 and PHP 8 or

## Table of Contents

- [Kirby 3 Cookieconsent plugin](#kirby-3-cookieconsent-plugin)
  - [Table of Contents](#table-of-contents)
  - [1. Installation](#1-installation)
    - [1.1 Composer](#11-composer)
    - [1.2 Download](#12-download)
    - [1.3 Git submodule](#13-git-submodule)
  - [2. Setup](#2-setup)
  - [3. Options](#3-options)
    - [3.1 Available options](#31-available-options)
    - [3.2 Defaults](#32-defaults)
    - [3.3. Types](#33-types)
    - [3.4. Provided cookie blocks](#34-provided-cookie-blocks)
    - [3.5 Extend](#35-extend)
  - [4. Practical examples](#4-practical-examples)
    - [4.1 Revisions](#41-revisions)
    - [4.2 Layout customization](#42-layout-customization)
    - [4.3 Autoclear cookies](#43-autoclear-cookies)

## 1. Installation

This version of the plugin requires PHP 8.0 and Kirby 3.6.0 or higher. The recommended way of installing is by using Composer.

### 1.1 Composer

```
composer require zephir/cookieconsent
```

### 1.2 Download

Download and copy this repository to `/site/plugins/cookieconsent`.

### 1.3 Git submodule

```
git submodule add https://github.com/zephir/kirby-cookieconsent.git site/plugins/cookieconsent
```

## 2. Setup

Add `snippet('cookieconsentCss')` to your header and `snippet('cookieconsentJs')` to your footer.
By default, the plugin displays the `simple` type with only accept/reject buttons and consent for necessary and measurement cookies.

## 3. Options

### 3.1 Available options

| Option        | Type    | Default                | Description                                                                                                                         |
| ------------- | ------- | ---------------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| type          | string  | `"simple"`             | The preconfigured plugin type. Use either `"simple"`, `"customizable"` or `null`/`false`. See [types](#types) for more information. |
| defaultLocale | string  | `"de"`                 | The fallback language if you don't use multiple languages.                                                                          |
| activeBlocks  | array   | [see below](#defaults) | Define which blocks are active, see [blocks](#default-cookie-blocks) for more information.                                          |
| extend        | array   | `[]`                   | Extend the `simple` / `customizable` configuration or provide your own if `null` / `false` is given as `type`.                      |
| cdn           | boolean | `false`                | Whether to load the cookieconsent assets from jsdelivr.net or use the compiled assets provided with this plugin.                    |

You can set all [cookieconset](https://github.com/orestbida/cookieconsent) options using the `extend` option.

### 3.2 Defaults

```php
[
    'type' => 'simple',
    'defaultLocale' => 'de',
    'activeBlocks' => [
        'necessary' => true,
        'functionality' => false,
        'experience' => false,
        'measurement' => true,
        'marketing' => false
    ],
    'extend' => [],
    'cdn' => false
]
```

### 3.3. Types

The `type` option can be used to load preconfigured variations of the cookieconsent plugin.

| Option value     | Description                                                                                                                                                                                                           |
| ---------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `"simple"`       | This type displays only the cookie title, description and an accept/reject button. The accept button will accept all enabled [blocks](#default-cookie-blocks). The reject button will only allow the necessary block. |
| `"customizable"` | With this type, the user will have an Accept button that accepts all enabled blocks and a Settings button that allows the user to customize which enabled [blocks](#default-cookie-blocks) they agree to.             |
| `null` / `false` | No default type will be loaded and you will have to provide all the settings yourself using the `extend' option.                                                                                                      |

### 3.4 Provided cookie blocks

Blocks allow you to granularly configure which scripts to load and which not to.
See [cookieconset](https://github.com/orestbida/cookieconsent) for more information.

Blocks provided by this plugin:

| Name          | Enabled | Description                                                                                                                          |
| ------------- | ------- | ------------------------------------------------------------------------------------------------------------------------------------ |
| necessary     | ✅      | The necessary cookies, can't be disabled by the user.                                                                                |
| functionality | ❌      | Cookies for basic functionality and communication.                                                                                   |
| experience    | ❌      | Cookies to improve the quality of the user experience and enable the user to interact with external content, networks and platforms. |
| measurement   | ✅      | Cookies that help to measure traffic and analyze behavior.                                                                           |
| marketing     | ❌      | These cookies help us to deliver personalized ads or marketing content to you, and to measure their performance.                     |

> In this context, "enabled" means that the cookie block is available on the website (and can be toggled by the user if not "necessary").

To enable/disable the blocks, use the `activeBlocks` options array.

You can then use these blocks to enable scripts when permission has been granted:

```js
<script type="text/plain" data-cookiecategory="necessary">
  console.log('Necessary scripts enabled');
</script>
```

See [cookieconsent#how-to-blockmanage-scripts](https://github.com/orestbida/cookieconsent#how-to-blockmanage-scripts) for more information.

### 3.5 Extend

With the `extend` option you can extend any of the options set by either type, or completly provide your own options for the cookieconsent js plugin.

If you extend one of the basic types, be aware that the language in the `languages` array (`extend.languages`) is `kirby` and the `current_language` is also `kirby`. This is so because we use the kirby translation option instead of the one provided by the cookieconsent js plugin.

## 4. Practical examples

> The PHP sections in the following sections will refer to the kirby `config.php`.

### 4.1 Revisions

In case of changes to the config, to show the cookie modal to people that already consented, you can use the `revision` option.
See [cookieconsent#how-to-manage-revision](https://github.com/orestbida/cookieconsent#how-to-manage-revisions).

<details>
  <summary>Code</summary>

```php
'zephir.cookieconsent' => [
    'extend' => [
        'revision' => 1
    ]
]
```

</details>

### 4.2 Layout customization

See [cookieconsent#layout-options--customization](https://github.com/orestbida/cookieconsent#layout-options--customization).

<details>
    <summary>Code</summary>

```php
'zephir.cookieconsent' => [
    'extend' => [
        'gui_options' => [
            'consent_modal' => [
                'layout' => 'bar',
                'position' => 'bottom center',
                'transition' => 'zoom',
                'swap_buttons' => false
            ],
            'settings_modal' => [
                'layout' => 'bar',
                'position' => 'left',
                'transition' => 'zoom'
            ]
        ]
    ]
]
```

</details>

### 4.3 Autoclear cookies

See [cookieconsent#how-to-clear-cookies](https://github.com/orestbida/cookieconsent#how-to-clear-cookies).

<details>
  <summary>Code</summary>

```php
use Zephir\Cookieconsent\OptionBlocks;

'zephir.cookieconsent' => [
    'type' => 'customizable',
    'defaultLocale' => 'en',
    'activeBlocks' => [
        'measurement' => false // Disable default measurement block
    ],
    'extend' => [
        'autoclear_cookies' => true,
        'languages' => [
            'kirby' => [
                // Make sure to use "kirby" as language
                'settings_modal' => [
                    'cookie_table_headers' => [
                        ['col1' => 'Name'],
                        ['col2' => 'Service'],
                        ['col3' => 'Description'],
                    ]
                ]
            ]
        ]
    ]
],
'ready' => function () { // Use ready to make use of the OptionBlocks class and kirby t() function
    return [
        'zephir.cookieconsent' => [
            'extend' => [
                'languages' => [
                    'kirby' => [
                        // Make sure to use "kirby" as language
                        'settings_modal' => [
                            'blocks' => [
                                array_merge(
                                    OptionBlocks::getMeasurement(), // Use the prepared measurement block
                                    [ // add cookie_table to measurement block
                                        "cookie_table" => [
                                            [
                                                "col1" => '^_ga',
                                                "col2" => 'Google Analytics',
                                                "col3" => t('custom.translation', null, option('zephir.cookieconsent.defaultLocale')),
                                                "is_regex" => true
                                            ],
                                            [
                                                "col1" => '_gid',
                                                "col2" => 'Google Analytics',
                                                "col3" => t('custom.translation', null, option('zephir.cookieconsent.defaultLocale')),
                                            ]
                                        ]
                                    ]
                                )
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];
}
```

</details>

## License

MIT

## Credits

- [Zephir](https://zephir.ch)
- [Marc Stampfli](https://github.com/themaaarc)
