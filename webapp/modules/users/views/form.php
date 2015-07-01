<?php
use webapp\modules\users_control\forms\Users_controlForm;
/**
 * @var Users_controlForm $form
 */

$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}</div>');
?>
<div class="col-xs-6">
	<?=
	$form->getForm(
		 [
			 'method'  => 'post',
			 'role'    => 'form',
		 ]
	); ?>
</div>
