<?php
namespace webapp\modules\pages\models;

use system\core\App;
use system\core\Model;

/**
 * Class Pages
 */
class Pages extends Model
{

	public static $table = 'pages';
	private static $routePath;

	private static function setRoutePath()
	{
		self::$routePath = App::getConfig()['modules']['pages']['routePath'];
	}

	public static function addRoute($alias)
	{
		self::setRoutePath();
		$routes                = file_get_contents(self::$routePath);
		$routesArr             = json_decode($routes, true);
		$routesArr['/'.$alias] = '/pages/show/'.$alias;
		return file_put_contents(self::$routePath, json_encode($routesArr));
	}

	public static function editRoute($alias, $newValue)
	{
		self::setRoutePath();
		$routes    = file_get_contents(self::$routePath);
		$routesArr = json_decode($routes, true);
		unset($routesArr['/'.$alias]);
		$newValue = str_replace('/', '', $newValue);
		$routesArr['/'.$newValue] = '/pages/show/'.$newValue;
		return file_put_contents(self::$routePath, json_encode($routesArr));
	}

	public static function removeRoute($alias)
	{
		self::setRoutePath();
		$routes    = file_get_contents(self::$routePath);
		$routesArr = json_decode($routes, true);
		unset($routesArr['/'.$alias]);
		return file_put_contents(self::$routePath, json_encode($routesArr));
	}
}
