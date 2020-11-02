<?php

return [
	'frontend' => [
		'ps/xo/html-polisher' => [
			'target' => \Ps\Xo\Middleware\HtmlPolisher::class,
			'before' => [
				'typo3/cms-frontend/content-length-headers'
			]
		],
	],
];