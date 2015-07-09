<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 19:10
 */
namespace webapp\assets;

use system\core\HTML\Asset;

class BootstrapAsset extends Asset
{

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	public function package()
	{
		return [
			'css' => [
				'/vendor/bootstrap/dist/css/bootstrap.min.css',
			]
		];
	}

	/**
	 * Массив других объектов-asset'ов, которые нужны этому в плане зависимости
	 * @return array
	 */
	public function dependency()
	{
		return [
		];
	}
}


