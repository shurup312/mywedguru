<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 19:10
 */
namespace webapp\assets;

use system\core\HTML\Asset;

class BootstrapFileInputAsset extends Asset
{

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	public function package()
	{
		return [
			'js'  => [
				'/vendor/bootstrap-fileinput/js/fileinput.js',
			],
			'css' => [
				'/vendor/bootstrap-fileinput/css/fileinput.css',
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
			JQueryAsset::className(),
		];
	}
}


