<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 23.06.2015
 * Time: 12:22
 */
namespace system\core\response\formats;

use system\core\App;

class ResponseString implements ResponseFormatInterface
{

	/**
	 * Прведение переданного контента к нужному для класса формату
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function run($content)
	{
		header('Content-type:text/html');
		return $content;
	}
}
