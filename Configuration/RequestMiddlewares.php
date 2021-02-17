<?php

return [
	'frontend' => [
		'ps/xo/html-polisher' => [
			'target' => \Ps\Xo\Middleware\HtmlPolisher::class,

			// keine Ahnung warum es auf after stehen muss -> eigentlich sollte diese Middleware davor ausgefuehrt werden
			// -> tut es aber nur mit after???
			'after' => [
				'typo3/cms-frontend/content-length-headers'
			]
		],

		// Ladereihenfolge veraendern -> da sonst statische Routen wie robots.txt unter /de/robots.txt versucht werden
		// aufzurufen
		'typo3/cms-frontend/base-redirect-resolver' => [
			'disabled' => true
		],

		'typo3/cms-frontend/base-redirect-resolver-override' => [
			'target' => \TYPO3\CMS\Frontend\Middleware\SiteBaseRedirectResolver::class,
			'after' => [
				'typo3/cms-frontend/static-route-resolver'
			],
			'before' => [
				'typo3/cms-redirects/redirecthandler'
			]
		],
	],
];