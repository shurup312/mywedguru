<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 01.04.15
 * Time: 18:30
 */
namespace system\core\base;

class Object
{

	public static function className()
	{
		return get_called_class();
	}
} 
