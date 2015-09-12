<?php
return [
	'fbAPI' => [
		'APPID'        => '1678928349004803',
		'SECURITY_KEY' => '8d9d9467fd8dbb6c2817599df2c0bf1b',
		'CLIENT_TOKEN' => 'daca01828fd28c522bc64e5b1b560fc6',
		'version'      => 2.3,
		'redirectURL'  => 'http://'.$_SERVER['HTTP_HOST'].'/auth/fb',
	],
	'vkAPI' => [
		'APPID'        => 3979503,
		'SECURITY_KEY' => '5RvUh5IU66fAdJuvjzMY',
		'PERMISSIONS'  => 'friends,status,wall,email,notifications,offline',
		'version'      => 5.34,
		'responseURL'  => 'http://'.$_SERVER['HTTP_HOST'].'/auth/vk',
		'SOMETEXT'     => 'mywed.guru',
	],
	'okAPI' => [
		'APPID'       => 1144697088,
		'PERMISSIONS' => 'VALUABLE_ACCESS',
		'PUBLIC_KEY'  => 'CBACLLDFEBABABABA',
		'PRIVATE_KEY' => '26EB47C70D10FBAF03A5A5F3',
		'URL'         => 'http://www.odnoklassniki.ru/game/1144697088',
		'redirectURL' => 'http://'.$_SERVER['HTTP_HOST'].'/auth/ok',
	],
	'loginURL'          => '/auth/',
	'basePage'          => '/',
];
