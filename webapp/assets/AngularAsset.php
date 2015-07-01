<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 15:26
 */
namespace webapp\assets;

use system\core\HTML\Asset;

class AngularAsset extends Asset
{

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	public function package()
	{
		return [
			'js'  => [
				'/vendor/angular/angular.js',
				'https://code.angularjs.org/1.3.9/angular-resource.js',
			]
			/**
			 * TODO: сделать через Bower
			 */
		];
	}

	/**
	 * Массив других объектов-asset'ов, которые нужны этому в плане зависимости
	 * @return array
	 */
	public function dependency()
	{
	}
}
