<?php
/*
 * Класс для работы с формами. Изначально строит просто форму.
 */
namespace system\core;
use webapp\modules\groups\model\Groups;

class forms extends app
{
    private static $form_key = false;
    public $table;
    public $oldData;
    public $visual=false;
    /**
     * @param array $atributes
     *
     * Атрибуты принимают параметры для отображеия формы
     */
    function startForm($atributes = false)
    {
        if (!empty($_SESSION['forms']))
        {
            unset($_SESSION['forms']);
        }
        echo "<form ";
            $this->printAttr($atributes);
        echo ">";
        self::$form_key = md5(time());
        $_SESSION['forms'][self::$form_key]="";
        $this->showInput(array("name"=>"form_key", "type"=>"hidden", "value"=>self::$form_key));
        if (!empty($this->oldData))
        {
            $this->showInput(array("name" => "id", "type" => "hidden", "value" => $this->oldData->id));
        }
        if (!empty($this->table)) {
            $this->showInput(array("name" => "table", "type" => "hidden", "value" => $this->table));
        } else { die('НЕ УКАЗАНА ТАБЛИЦА');}
        //echo self::$form_key;

    }
    function endForm()
    {
        echo "</form>";
    }

    /**
     * Функция построения поля типа input
     * @param array $atributes
     * @param array $rules
     *
     * Параметр $atributes принимает массив вида
     * array ("name"=>"login", "type"=>'email' ) и т.д. ВСЕ ЧТО НУЖНО :)
     *
     * Параметр $rules отвечает за проверки!! При этом он может сам определять как проверять, если
     * передан пустой параметр.
     */
    function showInput($atributes = false, $value=false, $label=false)
    {
        if (empty(self::$form_key)) {
            die("ФОРМУ НУЖНО НАЧИНАТЬ С startForm()");
        }
        if ($label)
        {
            echo "<label for=\"".$atributes['name']."\">".$label."</label>";
        }
        echo "<input ";
            $this->printAttr($atributes);
        if ($value) { echo "value='".$value."'"; }
        //проверяем наличие старых данных!!!
        if (!empty($this->oldData->$atributes['name']))
        {
            echo "value='".$this->oldData->$atributes['name']."'";
        }
        echo ">";


    }
    function printRights($head=false, $rights=false)
    {
        if (empty(self::$form_key)) {
            die("ФОРМУ НУЖНО НАЧИНАТЬ С startForm()");
        }
        if ($head)
        {
            echo "<label>".$head."</label>";
        }

        $this->grp = new Groups();
        $this->grp->printFromForms("rights[]", ((!empty($this->oldData->rights)) ? $this->oldData->rights : _SUPER_RIGHT_));
    }


    function showTextarea($atributes = false, $value=false, $label=false, $visual=false)
    {

        if ($visual!==false)
        {
            $this->visual[]=$atributes['name'];
        }
        if (empty(self::$form_key)) {
            die("ФОРМУ НУЖНО НАЧИНАТЬ С startForm()");
        }
        if ($label)
        {
            echo "<label for=\"".$atributes['name']."\">".$label."</label>";
        }
        echo "<textarea ";
        $this->printAttr($atributes);
        echo ">";
        if ($value) { echo $value; }
        if (!empty($this->oldData->$atributes['name']))
        {
            echo $this->oldData->$atributes['name'];
        }
        echo "</textarea>";


    }

    function showRadiobox($atributes = false, $values = false, $label=false, $head=false)
    {

        if (empty(self::$form_key)) {
            die("ФОРМУ НУЖНО НАЧИНАТЬ С startForm()");
        }
        if ($head)
        {
            echo "<label>".$head."</label>";
        }
        if (!isset($atributes['type'])) {
            $atributes['type'] = "radio";
        }

        foreach ($values as $k => $v) {
            if ($label)
            {
                echo "<label>";
            }
            $atributes['value'] = $k; //подставляем значения
            if (!empty($this->oldData->$atributes['name']))
            {
               if ($this->oldData->$atributes['name']==$k)
               {
                   $atributes['checked']='checked';
               } else { unset ($atributes['checked']);}
            }
            $this->showInput($atributes);
            echo " ".$v;
            if ($label)
            {
                echo "</label>";
            }
        }

    }

    /*
     * Вспомогательная функция для построения всех атрибутов
     */
    function printAttr($attr = false)
    {

        if (!empty($attr)) {
            foreach ($attr as $k => $v) {

                if (!empty($k) and !empty($v)) {
                    echo $k . "=\"" . $v . "\" ";
                }
            }
        }
    }

    /*
     * Функция для сохраенния данных переданых из формы !
     * $action = add - делает INSERT
     * edit = делает апдейт, но должен передан быть id
     * $redir - если пусто, то редиректим на HTTP_REFERER, иначе редиректим куда указано в $redir
     */
    function saveForm($action=false, $data=false, $redir=false)
    {

        if (empty($data)) {
            $post = $this->tools->getPost();
        } else {
            $post=$data;
        }
        /**************** ЕСЛИ ЭТО ПРАВА ***************/
        if (is_array($post['rights']))
        {
            $this->fs->includeFile(__DIR__."/../components/groups/model/Groups.php");
            $this->grp = new Groups();
            $rights = 0;
            $qty = count($post['rights']);
            reset($post['rights']);
            for ($i=0; $i<$qty; $i++)
            {
                $tmp = (int)$this->tools->strCheck($post['rights'][$i]);
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
            $post['rights'] = $rights;
        }
        /**************** /ЕСЛИ ЭТО ПРАВА ***************/
       // var_dump($post);
        //die();
        //если вдруг нам таблицу не передали. берем из данных
        if (empty($this->table)){ $this->table=$post['table'];}
        //смотрим что у нас в сессии
        if (!empty($post['form_key']))
        {
            $key=$post['form_key'];

        }
        //если нам известна таблица то все гуд :)
        if (!empty($this->table)) {
            /*
             * Получаем список полей таблицы
             */
            $this->db->get('SHOW COLUMNS FROM `' . $this->table . "`");
            /*
             * Начинаем формирование запросов
             */
            $query_add = "INSERT INTO `" . $this->table . "` VALUES (";
            $query_set = "UPDATE `".$this->table."` SET ";
            foreach ($this->db->arr as $k) {
                //var_dump($k);
                if ($k->Field=='id')
                {
                    if (!isset($post[$k->Field]))
                    { $id =0;} else {
                        $id = $post[$k->Field];
                        unset($post[$k->Field]);
                    }

                }
                if (!empty($post[$k->Field])) {

                    $query_add .= "'" . $post[$k->Field] . "',";
                    $query_set .= "`".$k->Field."` = '" . $post[$k->Field] . "',";
                } else {
                    //Если у поля ест значние по умолчанию используем его!!!
                    if (!empty($k->Default))
                    { $query_add .= "'".$k->Default."',";
                        $query_set .= "`".$k->Field."` = '" . $k->Default . "',";}
                    else {
                    $query_add .= "'',";

                        }
                }

            }
            $query_add = substr($query_add, 0, -1) . ");";
            $query_set = substr ($query_set,0,-1)." WHERE `id`='".$id."'";
            //die ($query_set);
            if ($action=='add') {
                $this->db->query($query_add);
                $this->db->insert_id();
            }
            if ($action=='update') {
                $this->db->query($query_set);
            }

            if ($this->db->res)
            {
                unset($_SESSION['forms']);
                if (empty($redir))
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                else 
                    header("Location: " . $redir);
            }
        }
    }
}

?>
