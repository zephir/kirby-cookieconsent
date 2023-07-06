<?php

# Use @include_once in case plugin is not installed through zip
# see https://getkirby.com/docs/guide/plugins/plugin-setup-composer#support-for-plugin-installation-without-composer
@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Http\Response;
use Zephir\Cookieconsent\Helpers;

$languages = require_once(__DIR__ . '/languages/lang.php');

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
						Helpers::getInitJs(),
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
		'type' => 'simple',
		'activeBlocks' => [
			'necessary' => true,
			'functionality' => true,
			'experience' => true,
			'measurement' => true,
			'marketing' => true
		],
		'override' => [],
		'content' => $languages['de'],
		'cdn' => false
	],
	'translations' => [
		'de' => $languages['de'],
		'en' => $languages['en']
	]
]);