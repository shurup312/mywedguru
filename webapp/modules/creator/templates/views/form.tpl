<?php
use webapp\modules\{ModuleName}\forms\{FormName};
/**
 * @var {FormName} $form
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
