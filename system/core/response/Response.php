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
}
