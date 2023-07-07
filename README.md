# Kirby 3 Cookieconsent plugin

![cover](docs/cover.jpg)

A plugin to implement [cookieconsent](https://github.com/orestbida/cookieconsent) in Kirby 3.

- Uses the open source cookieconsent library
- Provides two default configurations to get you started quickly
- Provides several "blocks" for different cookies
- Multilingual support
- Fully customizable

## Installation

### Download

Download and copy this repository to `/site/plugins/cookieconsent`.

### Git submodule

```
git submodule add https://github.com/zephir/kirby-cookieconsent.git site/plugins/cookieconsent
```

### Composer

```
composer require zephir/cookieconsent
```

## Setup

Add `snippet('cookieconsentCss')` to your header and `snippet('cookieconsentJs')` to your footer.
By default, the plugin displays the `simple` type with only accept/reject buttons and consent for necessary and measurement cookies.

## Options

| Option       | Type    | Default                | Description                                                                                                                                                       |
| ------------ | ------- | ---------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| type         | string  | `simple`               | The preconfigured plugin type. Use either `"simple"`, `"customizable"` or `null`/`false`. See [types](#types) for more information.                               |
| activeBlocks | array   | [see below](#defaults) | Define which blocks are active, see [blocks](#default-cookie-blocks) for more information.                                                                        |
| override     | array   | `[]`                   | Override the `simple` / `customizable` configuration or provide your own if `null` / `false` is given as `type`.                                                  |
| content      | array   | [see below](#defaults) | The labels that are used in the `simple` / `customizable` type if you don't use a multilingual setup. These are irrelevant if you provide your own configuration. |
| cdn          | boolean | false                  | Whether to load the cookieconsent assets from jsdelivr.net or use the compiled assets provided with this plugin.                                                  |

You can set all [cookieconset](https://github.com/orestbida/cookieconsent) options using the `override` option.

### Defaults

```php
[
    'type' => 'simple',
    'activeBlocks' => [
        'necessary' => true,
        'functionality' => false,
        'experience' => false,
        'measurement' => true,
        'marketing' => false
    ],
    'override' => [],
    'content' => $languages['de'],
    'cdn' => false
]
```

## Types

The `type` option can be used to load preconfigured variations of the cookieconsent plugin.

| Option value     | Description                                                                                                                                                                                                           |
| ---------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `"simple"`       | This type displays only the cookie title, description and an accept/reject button. The accept button will accept all enabled [blocks](#default-cookie-blocks). The reject button will only allow the necessary block. |
| `"customizable"` | With this type, the user will have an Accept button that accepts all enabled blocks and a Settings button that allows the user to customize which enabled [blocks](#default-cookie-blocks) they agree to.             |
| `null` / `false` | No default type will be loaded and you will have to provide all the settings yourself using the `override' option.                                                                                                    |

## Default cookie blocks

Blocks allow you to granularly configure which scripts to load and which not to.
See [cookieconset](https://github.com/orestbida/cookieconsent) for more information.

Default blocks provided by this plugin:

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

## License

MIT

## Credits

- [Zephir](https://zephir.ch)
- [Marc Stampfli](https://github.com/themaaarc)
