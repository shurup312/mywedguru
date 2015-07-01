<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 19:05
 */


namespace webapp\assets;

use system\core\HTML\Asset;

class AngularCookiesAsset extends Asset{

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	public function package()
	{
		return [
			'js' => [
				'/vendor/angular-cookies/angular-cookies.js',
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
			AngularAsset::className(),
		];
	}
}
