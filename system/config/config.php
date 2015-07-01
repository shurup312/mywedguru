<?php
/*
 * Настройка Базы данных
 */
use system\core\App;

\system\core\App::setAlias('webroot', _ROOT_PATH_);
\system\core\App::setAlias('webapp', _WEBAPP_);
\system\core\App::setAlias('system', __DIR__);
$config     = [
	'webappFolder' => \system\core\App::getPathOfAlias('webapp'),
	'systemFolder' => \system\core\App::getPathOfAlias('system'),
	'debug'        => true,
	'dateFormat'   => 'd.m.Y H:i:s',
];
$configPath = App::getPathOfAlias('webapp.config.config');
if (file_exists($configPath)) {
	$projectConfig = require $configPath;
	$config        = \system\core\helpers\ArrayHelper::merge($config, $projectConfig);
}
if ($config['debug']) {
	error_reporting(E_ALL);
}
return $config;
