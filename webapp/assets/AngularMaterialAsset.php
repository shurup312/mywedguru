<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 18:37
 */
namespace webapp\assets;

use system\core\HTML\Asset;

class AngularMaterialAsset extends Asset
{

	public function package()
	{
		return [
			'css' => [
				'/vendor/angular-material/angular-material.css',
			],
			'js'  => [
				'/vendor/angular-animate/angular-animate.min.js',
				'/vendor/angular-aria/angular-aria.js',
				'/vendor/angular-material/angular-material.js',
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
