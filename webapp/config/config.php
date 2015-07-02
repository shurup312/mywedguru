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
	'vkAPI'             => [
		'APPID'        => 3979503,
		'SECURITY_KEY' => '5RvUh5IU66fAdJuvjzMY',
		'PERMISSIONS'  => 'friends,status,wall,email,notifications,offline',
		'version'      => 5.34,
		'responseURL'      => 'http://'.$_SERVER['HTTP_HOST'].'/auth/vk',
		'SOMETEXT'      => 'mywed.guru',
	],
	'okAPI' => [
		'APPID' => 1144440832,
		'PERMISSIONS' => 'VALUABLE_ACCESS',
		'PUBLIC_KEY' => 'CBAJMHDFEBABABABA',
		'PRIVATE_KEY' => '5587F0FEA43628FECFE09738',
		'URL' => 'http://www.odnoklassniki.ru/game/1144440832',
		'redirectURL' => 'http://'.$_SERVER['HTTP_HOST'].'/auth/ok',
	],
	'defaultController' => false,
		'loginURL'          => '/auth/',
	'modules'           => [
		"adm"         => [],
		"auth"        => [],
		"blocks"      => [],
		"cabinet"     => [
			//'imageFolder' => App::getPathOfAlias('webroot.public.components.cabinet'),
			'thmbWidth'  => 300,
			'thmbHeight' => 300,
		],
		"creator"     => [],
		'main'        => [],
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
		'/' => '/auth'
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
