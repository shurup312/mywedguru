<?php

$form= new $this->forms();
$form->table='menu_core';
if (!empty($data)) {
    $form->oldData=$data;
    $form->startForm(array("action"=>"/menu_core/edit", "id"=>"menu_core", "method"=>"post", "class"=>"ajax", "enctype"=>"multipart/form-data" ));
} else {
    $form->startForm(array("action"=>"/menu_core/add", "id"=>"menu_core", "method"=>"post","class"=>"ajax", "enctype"=>"multipart/form-data" ));
    /*
     * ПЕРЕДАН ПАРАМЕТР РОДИТЕЛЯ!!!
     */
    if (!empty($_POST['id']))
    {
        $form->showInput(array("name"=>"pid", 'value'=>intval($_POST['id']),"type"=>'hidden'));

    }
}
?>
<div class="form-group">
    <?php $form->showInput(array("name"=>"MenuName", "class"=>"form-control", "required"=>"1"),'','Название меню');?>
    <?php $form->showInput(array("name"=>"type", "value"=>"fol", "type"=>"hidden"),'','');?>
    <?php $form->showInput(array("name"=>"backlink", "value"=>$_SERVER['HTTP_REFERER'], "type"=>"hidden"),'','');?>
</div>
<div class="form-group flt"><?php $form->printRights('Укажите права доступа'); ?> </div>

<div class="form-group clear_pages">
    <?php $form->showInput(array("name"=>"SendAndClose", "type"=>"submit", "class"=>"btn btn-success"),'Сохранить и закрыть');?>
    <a href="<?=$_SERVER['HTTP_REFERER']?>" class="btn btn-danger">Вернуться</a>

</div>
<?php $form->endForm();
?>

