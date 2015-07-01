<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 19:08
 */
namespace webapp\assets;

use system\core\HTML\Asset;

class JQueryAsset extends Asset
{

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	public function package()
	{
		return [
			'js' => [
				'/vendor/jquery/dist/jquery.min.js',
			]
		];
	}

	/**
	 * Массив других объектов-asset'ов, которые нужны этому в плане зависимости
	 * @return array
	 */
	public function dependency()
	{
		// TODO: Implement dependency() method.
	}
}
