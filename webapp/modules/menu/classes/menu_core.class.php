<?php

class menu_core extends system\core\App
{
    public $act = false;

    /*
     * Констурктор смотрит на переданныей гет параметр и
     * от этого показыват шаблон и манипулирует данными
     */
    function __construct()
    {
        /***********
         * Пост данные есть
         */
        if (!empty($_POST))
        {
            /*
             * Ссылка
             */
            if (!empty($_POST['link']))
            {
                if (substr($_POST['link'],0,1)!="/")
                {

                    $_POST['link']="/".$_POST['link'];
                }
            }
            /*
             * Права
             */
            if (isset($_POST['rights']) and is_array($_POST['rights']))
            {

                $this->grp = new \webapp\modules\groups\model\Groups();
                $rights = 0;
                $qty = count($_POST['rights']);
                reset($_POST['rights']);
                for ($i=0; $i<$qty; $i++)
                {
                    $tmp = (int)$this->tools->strCheck($_POST['rights'][$i]);
                    if ($tmp == 1)
                    {
                        $rights |= 1;
                    }
                    else if ($tmp == 2)
                    {
                        $rights |= 2;
                    }
                    else
                    {
                        if ($this->grp->getGroup("value", $tmp))
                        {
                            $rights |= $this->grp->group['rights'];
                        }
                    }
                }
                $_POST['rights'] = $rights;
            }
        }
        if (count(self::$url) > 1) {
            $this->act = self::$url[2];
        }
        switch ($this->act) {
            case false:

                /*
                Получаем записи типа fol - (folders) папки..
                */
                $this->mdb->get("SELECT * FROM `menu_list` WHERE `type` = 'fol' ");
                // var_dump($this->mdb);
                $this->fs->includeFile(__DIR__ . "/../templates/menu_list.php", $this->mdb->arr);
                break;
            case "newform":
                $this->fs->includeFile(__DIR__ . "/../templates/form.php");
                break;
            case "editform":
                $this->noId();
                $data = $this->mdb->getRow("SELECT * FROM `menu_list` WHERE `id`=?i", intval($_GET['id']));
                $this->fs->includeFile(__DIR__ . "/../templates/form.php", $data);
                break;
            case "add":
                $this->mdb->save("menu_list", $this->tools->getPost());
                header("Location: /menu_core");
                break;
            case "edit":
                $this->mdb->save("menu_list", $this->tools->getPost());
                header("Location: /menu_core");
                break;
            case "del":
                $this->noId();
                $this->mdb->query("DELETE FROM `menu_list` WHERE `id`=?i or `pid`=?i", intval($_GET['id']), intval($_GET['id']));
                header("Location:".$_SERVER['HTTP_REFERER']);
                break;
            /************** РАБОТА С ПУНКТАМИ **************/
            case "menuList":
                $this->noId();
                $this->mdb->get("SELECT * FROM `menu_list` WHERE `type` != 'fol' and `pid`=?i ORDER BY `order`", intval($_GET['id']));
                // var_dump($this->mdb);
                $this->fs->includeFile(__DIR__ . "/../templates/punkt_list.php", $this->mdb->arr);
                break;
            case "newPunkt":
                $this->noId();
                $this->fs->includeFile(__DIR__ . "/../templates/punkt_form.php");
                break;
            case "add_punkt":
                $this->mdb->save("menu_list", $this->tools->getPost());
                header("Location: /menu_core/menuList?id=".intval($_POST['pid']));
                break;
            case "edit_punkt":

                $this->mdb->save("menu_list", $this->tools->getPost());
                header("Location: ".$this->tools->strCheck($_POST['backlink']));
                break;
            case "editPunkt":
                $data = $this->mdb->getRow("SELECT * FROM `menu_list` WHERE `id`=?i", intval($_GET['id']));
                $this->fs->includeFile(__DIR__ . "/../templates/punkt_form.php", $data);
                break;
            case "orderList":
                if(!empty($_POST['items']) && (!empty($_POST['pid'])))
                {
                    $items_arr = explode(',', $_POST['items']); // Массив элементов меню
                    $pid       = (int)$_POST['pid']; // ID основного меню
                    // Записываем порядок пунктов меню
                    for ($i = 0; $i < count($items_arr); $i++) 
                    {
                        $order = $i + 1;
                        $values = array("order" => $order);
                        $this->mdb->set('menu_list', $this->mdb->parse("?n=?i", "id", $items_arr[$i]), $values);
                    }
                    // Перерисовываем меню слева, если изменили его
                    if ($pid == 1)
                    {
                        $this->fs->includeFile(_COMP_PATH_."menu_core/classes/front_menu_core.class.php");
                        $menu=new front_menu_core();
                        $menu->show_menu(1,'admin_left');
                    }
                }
                else
                {
                    echo 'error';
                }
                die();
                break;
        }

    }

    function noId()
    {
        if (empty($_GET['id'])) {
            die ("НЕ ПЕРЕДАН ПАРАМЕТР");
        }
    }

}
?>
