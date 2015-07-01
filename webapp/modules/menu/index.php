<?php
use system\core\App;
/*
* Устанавливаем дизайн
*/
App::html()->design = "@admin";
App::html()->title = "Mind CMS - Управление меню";
App::html()->header = "<a href=\"/menu_system\core\">Управление меню</a>";
/*
 * Проверяем права
 */
App::go()->rights->isRights(_ADMIN_RIGHT_);
App::go()->SetControllerAsContent();
App::html()->setJs(_ROOT_PATH_."/public/components/menu_core/script.js");
App::go()->fs->includeFile(__DIR__."/classes/menu_core.class.php");
$menu_core= new menu_core();
?>
