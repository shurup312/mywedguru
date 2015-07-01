<div class="well">Создавайте новые пункты для этого меню, на ваш вкус и цвет.<div id="load" style='float:right;padding:0px;margin:-5px;'></div></div>

<?php
error_reporting(E_ALL);
$this->html->header.=" / <a href=\"/menu_core/newPunkt?id=".intval($_GET['id'])."\" class=\"btn bnt-xs btn-success\">Новый пункт</a>";
$this->html->setJs("//code.jquery.com/jquery-migrate-1.2.1.min.js");
$this->html->setJs("/public/components/menu_core/table.dnd.js");
$this->html->setJs("/public/components/menu_core/punkt.js");
$this->html->setJs("/templates/admin/js/jquery-ui-1.9.2.custom.min.js");
$this->html->setCss("/public/components/menu_core/style.css");

if (count($data)>=1)
{
    ?>
    <table class="table" id="table">
        <thead>
            <tr>
                <th width="10"></th>
                <th>ID</th>
                <th>Название меню</th>
                <th style="text-align: right;">Действия</th>
            </tr>
        </thead>
        <?php
        foreach ($data as $k=>$v)
        {
            ($k % 2 == 0) ? $class = 'alt' : $class = '';?>
            <tr id="<?=$v->id?>" pid="<?=$v->pid?>">
                <td class="move"><i class="glyphicon glyphicon-move dragHandle"></i></td>
                <td><?=$v->id;?></td>
                <td><?=$this->tools->strCheckDecode($v->MenuName);?></td>
                <td style="text-align: right;">
                    <a href="<?=$v->link;?>" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-link"></i> Перейти</a>
                    <a href="/menu_core/editPunkt?id=<?=$v->id;?>" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Поправить</a>
                    <a href="/menu_core/del?id=<?=$v->id;?>" class="btn btn-xs btn-danger"><i class="fa-trash-o fa"></i> Удалить</a>
                </td>
            </tr>
        <?php

        }
        ?>
    </table>
    <p class="result"></p>
    <?php
}

