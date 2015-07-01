<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 14.05.15
 * Time: 15:01
 */
/**
 * @var \webapp\modules\creator\forms\CreatorForm $form
 */
$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}</div>');
echo $form->getForm(
	 [
		 'method'  => 'post',
		 'role'    => 'form',
	 ]
);
