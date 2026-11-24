<?php

namespace Zephir\Cookieconsent;

use InvalidArgumentException;

/**
 * A piece of raw JavaScript that is emitted unquoted into the generated
 * `CookieConsent.run({...})` config, e.g. a callback function or a RegExp.
 *
 * Everything else in the config is still `json_encode`d.
 *
 * ```php
 * use Zephir\Cookieconsent\Js;
 *
 * 'onConsent' => Js::raw('function ({cookie}) { console.log(cookie); }'),
 * 'autoClear' => ['cookies' => [['name' => Js::regex('^_ga')]]],
 * ```
 */
final class Js
{
    private function __construct(public readonly string $code)
    {
    }

    /**
     * Raw JavaScript expression, emitted as is. Only pass trusted code
     * (your own config), never user input.
     */
    public static function raw(string $code): self
    {
        if (trim($code) === '') {
            throw new InvalidArgumentException('Js::raw() needs a non-empty JavaScript expression.');
        }

        return new self($code);
    }

    /**
     * A RegExp built from a pattern string, without the surrounding slashes:
     * `Js::regex('^_ga')` → `new RegExp("^_ga", "")`, which matches `/^_ga/`.
     * The pattern and flags are JSON encoded, so they need no JS escaping.
     */
    public static function regex(string $pattern, string $flags = ''): self
    {
        if (preg_match('/^[dgimsuvy]*$/', $flags) !== 1) {
            throw new InvalidArgumentException(sprintf('Invalid RegExp flags "%s".', $flags));
        }

        return new self(sprintf(
            'new RegExp(%s, %s)',
            Configuration::encode($pattern),
            Configuration::encode($flags)
        ));
    }

    /**
     * Encodes a config value as a JavaScript expression: JSON, except for
     * `Js` instances anywhere in it, which are inserted unquoted.
     */
    public static function encode(mixed $value): string
    {
        $snippets = [];
        // A random token per call, so no ordinary string in the config can collide with a placeholder.
        $token = bin2hex(random_bytes(8));

        $replace = function (mixed $value) use (&$replace, &$snippets, $token): mixed {
            if ($value instanceof self) {
                $placeholder = '__kcc_js_' . $token . '_' . count($snippets) . '__';
                // Parentheses keep the expression self-contained, the newline ends a trailing `//` comment.
                $snippets['"' . $placeholder . '"'] = '(' . $value->code . "\n)";

                return $placeholder;
            }

            if (is_array($value)) {
                return array_map($replace, $value);
            }

            return $value;
        };

        $json = Configuration::encode($replace($value));

        return strtr($json, $snippets);
    }
}
