<?php

use Kirby\Toolkit\Html;

/**
 * The tags are built with Html::tag() instead of js() on purpose: the URLs are
 * already versioned, and js() would pass them through `js` components such as
 * cachebuster plugins, which rewrite them into URLs Kirby cannot resolve.
 */
$attr = option('zephir.cookieconsent.cdn')
    ? [
        'src' => 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.3.0/dist/cookieconsent.umd.js',
        'integrity' => 'sha384-IcEhyS/6Gw/4ltNrdjg54dtIIW42QAapmyUllUalmQ2Exmd8docXcuKnUVMIC7I6',
        'crossorigin' => 'anonymous'
    ]
    : ['src' => kirby()->plugin('zephir/cookieconsent')->asset('index.js')->url()];

$language = option('languages') ? kirby()->language() : null;

// The config script is cached by browsers (see the route in index.php), so its
// URL carries a hash of everything it is built from: a new revision, category
// or translation then gets a new URL instead of a stale cached copy.
$version = hash('crc32b', json_encode([
    kirby()->plugin('zephir/cookieconsent')->version(),
    option('zephir.cookieconsent'),
    $language?->code()
]));

$configUrl = url(($language ? $language->path() : '') . '/cookieconsent.js') . '?v=' . $version;

echo Html::tag('script', '', ['defer' => true, ...$attr]) . PHP_EOL;
echo Html::tag('script', '', ['defer' => true, 'src' => $configUrl]) . PHP_EOL;
