<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 07.05.15
 * Time: 21:33
 */
use webapp\modules\course\webapp\modules\admin\forms\CoursesForm;

/**
 * @var CoursesForm $form
 */
$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}</div>');
?>
<?=
$form->getForm(
	[
		'method' => 'post',
		'role'   => 'form',
	]
); ?>
<script type="text/javascript" src="/templates/admin/js/ckeditor/ckeditor.js"></script>
