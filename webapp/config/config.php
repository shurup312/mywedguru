<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 23.06.2015
 * Time: 16:27
 */
use system\core\App;
use system\core\helpers\ArrayHelper;

$projectConfig          = [
	'mail'              => [
		'class'      => \webapp\components\EmailComponent::className(),
		'host'       => '',
		'login'      => '',
		'password'   => '',
		'port'       => 465,
		'SMTPSecure' => 'ssl'
	],
	'fbAPI'             => [
		'APPID'        => '1678928349004803',
		'SECURITY_KEY' => '8d9d9467fd8dbb6c2817599df2c0bf1b',
		'CLIENT_TOKEN' => 'daca01828fd28c522bc64e5b1b560fc6',
		'version'      => 2.3,
		'redirectURL'  => 'http://'.$_SERVER['HTTP_HOST'].'/auth/fb',
	],
	'vkAPI'             => [
		'APPID'        => 3979503,
		'SECURITY_KEY' => '5RvUh5IU66fAdJuvjzMY',
		'PERMISSIONS'  => 'friends,status,wall,email,notifications,offline',
		'version'      => 5.34,
		'responseURL'  => 'http://'.$_SERVER['HTTP_HOST'].'/auth/vk',
		'SOMETEXT'     => 'mywed.guru',
	],
	'okAPI'             => [
		'APPID'       => 1144697088,
		'PERMISSIONS' => 'VALUABLE_ACCESS',
		'PUBLIC_KEY'  => 'CBACLLDFEBABABABA',
		'PRIVATE_KEY' => '26EB47C70D10FBAF03A5A5F3',
		'URL'         => 'http://www.odnoklassniki.ru/game/1144697088',
		'redirectURL' => 'http://'.$_SERVER['HTTP_HOST'].'/auth/ok',
	],
	'defaultController' => false,
	'loginURL'          => '/auth/',
	'basePage'          => '/',
	'modules'           => [
		"adm"         => [],
		"auth"        => [],
		"blocks"      => [],
		"cabinet"     => [
			'imageFolder' => App::getPathOfAlias('webroot.public.components.cabinet'),
			'thmbWidth'   => 300,
			'thmbHeight'  => 300,
		],
		"creator"     => [],
		'catalog'        => [
			'modules'=>[
				'photo'=>[

				]
			]
		],
		"pages"       => [
			'routePath' => App::getPathOfAlias('webapp.modules.pages.config').'route.cfg',
		],
		"users"       => [],
		'filemanager' => [
			'root'            => '/filemanager',
			'baseCloudPath'   => __DIR__.'/../../cloud',
			'cloudUrl'        => '/filemanager/content?file=',
			// null - отключить контроль, [] - запретить все
			'allowMimeTypes'  => null,
			// null - отключить контроль, [] - разрешить все
			'denyMimeTypes'   => null,
			// null - отключить контроль памяти
			'availableMemory' => null,
		],
	],
	'components'        => [
		'user' => [
			'class' => \webapp\components\UserComponent::className(),
		],
	],
	'emails'            => [
		'dev' => 'shurup@e-mind.ru',
	],
	'paths'             => [
		'/' => '/catalog'
	],
];
$pagesRoutes            = $projectConfig['modules']['pages']['routePath'];
$pagesRoutes            = file_get_contents($pagesRoutes);
$pagesRoutesArr         = json_decode($pagesRoutes, false);
$projectConfig['paths'] = ArrayHelper::merge($projectConfig['paths'], $pagesRoutesArr);
$localConfigFile        = __DIR__.DIRECTORY_SEPARATOR.'local-config.php';
if (file_exists($localConfigFile)) {
	$localConfigArray = require $localConfigFile;
	$projectConfig    = ArrayHelper::merge($projectConfig, $localConfigArray);
}
return $projectConfig;
