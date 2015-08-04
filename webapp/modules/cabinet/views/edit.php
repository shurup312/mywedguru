<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 01.07.2015
 * Time: 14:04
 */
use webapp\modules\cabinet\forms\UserForm;
use webapp\modules\cabinet\models\UserExtend;

/**
 * @var UserForm $form
 * @var UserExtend $user
 */
$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}{error}</div>');
?>
<div class="col-xs-6">
	<?=
	$form->getForm(
		 [
			 'method'  => 'post',
			 'action'  => '/cabinet/save',
			 'enctype' => 'multipart/form-data',
		 ]
	); ?>
</div>
<div class="col-xs-6">
	<? if(isset($user['avatar'])){ ?>
		<div class="aler">
			Если необходимо сменить фотографию, то просто загрузите другую.
		</div>
		<img src="/public/components/cabinet/<?= $user['avatar']; ?>" alt=""/><?
	}?>
</div>
