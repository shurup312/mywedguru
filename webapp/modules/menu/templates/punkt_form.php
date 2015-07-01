<?php

$form= new $this->forms();
$this->html->setCss("/public/components/menu_core/style.css");
$form->table='menu_core';
if (!empty($data)) {
    $form->oldData=$data;
    $form->startForm(array("action"=>"/menu_core/edit_punkt", "id"=>"menu_core", "method"=>"post", "class"=>"ajax", "enctype"=>"multipart/form-data" ));
} else {
    $form->startForm(array("action"=>"/menu_core/add_punkt", "id"=>"menu_core", "method"=>"post","class"=>"ajax", "enctype"=>"multipart/form-data" ));
    /*
     * ПЕРЕДАН ПАРАМЕТР РОДИТЕЛЯ!!!
     */
    if (!empty($_GET['id']))
    {
        $form->showInput(array("name"=>"pid", 'value'=>intval($_GET['id']),"type"=>'hidden'));

    }
}
?>
<div class="form-group flt clear_menu">
    <?php $form->showInput(array("name"=>"type", "value"=>'lin', "type"=>"hidden"),'','');?>
     <?php /* $form->showRadiobox(array("name"=>"type", "required"=>'1'),array("lin"=>" - Ссылка на страницу или ресурс ","page"=>" - Страница "),true,'Какой пункт мы хотим добавить? '); */ ?>
    </div>
<div class="form-group clear_menu">
    <?php $form->showInput(array("name"=>"MenuName", "class"=>"form-control", "required"=>"1"),'','Название для меню (текст который будет ссылкой)');?>
    <?php $form->showInput(array("name"=>"link", "class"=>"form-control", "required"=>"1"),'','ССЫЛКА');?>
     <?php $form->showInput(array("name"=>"backlink", "value"=>$_SERVER['HTTP_REFERER'], "type"=>"hidden"),'','');?>
    <?php $form->showInput(array("name"=>"visible", "value"=>'y', "type"=>"hidden"),'','');?>

</div>
<div class="form-group flt"><?php $form->printRights('Укажите права доступа'); ?> </div>

<div class="form-group clear_pages">
    <?php $form->showInput(array("name"=>"SendAndClose", "type"=>"submit", "class"=>"btn btn-success"),'Сохранить и закрыть');?>
    <a href="<?=$_SERVER['HTTP_REFERER']?>" class="btn btn-danger">Вернуться</a>

</div>
<?php $form->endForm();
?>

