<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 24.03.15
 * Time: 18:48
 */
namespace webapp\assets;

use system\core\HTML\Asset;

class DataTablesAsset extends Asset
{

	/**
	 * Список скриптов и css для подключения
	 * @return array ['css' => array, 'js' => array]
	 */
	public function package()
	{
		return [
			'js'  => [
				'/vendor/datatables/media/js/jquery.dataTables.js',
				'/vendor/jquery.dataTables.columnFilter/index.js',
			],
			'css' => [
				'/vendor/datatables/media/css/jquery.dataTables.min.css',
			],
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
