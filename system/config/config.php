<?php
/*
 * Настройка Базы данных
 */
use system\core\App;

\system\core\App::setAlias('webroot', _ROOT_PATH_);
\system\core\App::setAlias('webapp', _WEBAPP_);
\system\core\App::setAlias('system', _WEBAPP_.DIRECTORY_SEPARATOR.'..');
$runtimeFolder = _WEBAPP_.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'runtime';
if(!file_exists($runtimeFolder)){
	mkdir($runtimeFolder,0777, true);
	mkdir($runtimeFolder.DIRECTORY_SEPARATOR.'logs',0777, true);
}
\system\core\App::setAlias('runtime', $runtimeFolder);
$config     = [
	'webappFolder' => \system\core\App::getPathOfAlias('webapp'),
	'systemFolder' => \system\core\App::getPathOfAlias('system'),
	'runtimeFolder' => \system\core\App::getPathOfAlias('runtime'),
	'debug'        => true,
	'dateFormat'   => 'd.m.Y H:i:s',
];
$configPath = App::getPathOfAlias('webapp.config.config');
if (file_exists($configPath)) {
	$projectConfig = require $configPath;
	$config        = \system\core\helpers\ArrayHelper::merge($config, $projectConfig);
}
$configPath = App::getPathOfAlias('webapp.config.config-local');
if (file_exists($configPath)) {
	$projectConfig = require $configPath;
	$config        = \system\core\helpers\ArrayHelper::merge($config, $projectConfig);
}
if ($config['debug']) {
	error_reporting(E_ALL);
}
return $config;
