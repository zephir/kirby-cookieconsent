<?php

use Kirby\Toolkit\Html;

/**
 * The tag is built with Html::tag() instead of css() on purpose: the URL is
 * already versioned, and css() would pass it through `css` components such as
 * cachebuster plugins, which rewrite it into a URL Kirby cannot resolve.
 */
$attr = option('zephir.cookieconsent.cdn')
    ? [
        'href' => 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.3.0/dist/cookieconsent.css',
        'integrity' => 'sha384-J2G7WCDaSVMf7TcKzuKtLDOeYx0euWJAKBZwt+oKF3cTeiZUFc1B3OrsXktUVRXy',
        'crossorigin' => 'anonymous'
    ]
    : ['href' => kirby()->plugin('zephir/cookieconsent')->asset('index.css')->url()];

echo Html::tag('link', null, ['rel' => 'stylesheet', ...$attr]) . PHP_EOL;
