<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 09.07.2015
 * Time: 16:37
 */
/**
 * @var \webapp\modules\auth\forms\RegistrationForm $form
 */
\webapp\assets\BootstrapAsset::init();
$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}</div>');
?>
<div class="col-xs-6">
	<?=
	$form->getForm(
		 [
			 'method'  => 'post',
			 'role'  => 'form',
			 'enctype' => 'multipart/form-data',
		 ]
	); ?>
</div>
<a href="/auth/checktype">назад на выбор типа пользователя</a>
