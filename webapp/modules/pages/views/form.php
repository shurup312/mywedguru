<?php
use webapp\modules\pages\forms\PagesForm;

/**
 * @var PagesForm $form
 */
$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}</div>');
?>
<?=
$form->open(
	[
		'method' => 'post',
		'role'   => 'form',
	]
); ?>
<div class="col-xs-6">
	<?= $form->getElement('menu_name'); ?>
	<?= $form->getElement('title'); ?>
	<?= $form->getElement('url'); ?>
	<?= $form->getElement('content'); ?>
</div>
<div class="col-xs-6">
	<?= $form->getElement('meta_title'); ?>
	<?= $form->getElement('meta_keywords'); ?>
	<?= $form->getElement('meta_description'); ?>
	<div class="alert alert-info">
		<?= $form->getElement('rights'); ?>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<?= $form->getElement('submit'); ?>
	</div>
</div>
<?= $form->close() ?>
<script type="text/javascript" src="/templates/admin/js/ckeditor/ckeditor.js"></script>
<?
$js = '/templates/admin/js/app/pages/ckinit.js';
\system\core\App::html()->setJs($js);
?>
