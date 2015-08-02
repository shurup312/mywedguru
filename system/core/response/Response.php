<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 23.06.2015
 * Time: 10:04
 */
namespace system\core\response;

use system\core\base\Object;
use system\core\response\formats\ResponseJSON;
use system\core\response\formats\ResponseString;
use Exception;

class Response extends Object
{
	const STRING_FORMAT = 1;
	const JSON_FORMAT = 2;
	const STATUS_403 = 403;
	const STATUS_404 = 404;
	const STATUS_301 = 301;
	const STATUS_500 = 500;
	const STATUS_200 = 200;
	public static $format = self::STRING_FORMAT;

	public static function run($content)
	{
		if(self::$format == self::STRING_FORMAT){
			return ResponseString::run($content);
		}
		if(self::$format == self::JSON_FORMAT){
			return ResponseJSON::run($content);
		}
		throw new Exception('Неверно указан формат вывода ');
	}

	public function setFormat($format){
		self::$format = $format;
	}

	public function redirect($url){
		header('Location: '.$url);
		die();
	}

	public function setStatus($status)
	{
		switch($status){
			case self::STATUS_301:
				header('HTTP/1.1 301 Moved Permanently');
				break;
			case self::STATUS_500:
				header('HTTP/1.1 500 Internal Server Error');
				break;
			case self::STATUS_200:
				header('HTTP/1.1 200 OK');
				break;
			case self::STATUS_403:
				header('HTTP/1.1 403 Forbidden');
				break;
			case self::STATUS_404:
				header('HTTP/1.0 404 Not Found');
				break;
		}
	}
}
