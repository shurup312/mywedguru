<?php
namespace webapp\modules\menu\classes;
use system\core\App;
use Exception;
use webapp\modules\menu\models\MenuList;

/**
 * Created by PhpStorm.
 * User: Женя
 * Date: 28.02.2015
 * Time: 22:20
 */
class FrontMenuCore extends App
{

	function show_menu($id = false, $template = false)
	{
		if ($id==false) {
			throw new Exception('Не указан ID меню, которое нужно вызвать.');
		}
		$menuList = MenuList::factory()->where('pid', intval($id))->rawWhere('rights&'.App::get('user')->rights)->findMany();
		if ($template==false) {
			$template = "default";
		}
		require_once(_ROOT_PATH_."/public/components/menu_core/templates/".$template.".php");
	}
}

?>
