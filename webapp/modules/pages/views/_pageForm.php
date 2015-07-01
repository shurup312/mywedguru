<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 04.06.2015
 * Time: 12:14
 */

use webapp\modules\pages\forms\PagesForm;
use webapp\modules\pages\models\Pages;

/**
 * @var Pages     $pageData
 * @var PagesForm $form
 */
?>
<?
$form->setTemplate('<div class="form-group"><label for="name">{label}</label>{element}</div>');
?>
<style type="text/css">
	.notify-wrapper {
		width: 100%;
		height: 100%;
		background-color: rgba(22, 22, 22, .4);
		position: fixed;
		left: 0;
		top: 0;
		z-index: 2;
		display: none;
	}

	.notify-wrapper > div {
		position: fixed;
		width: 45%;
		z-index: 999;
		top: 40%;
		margin: 0 auto;
		left: 30%;
	}
</style>
<div class="notify-wrapper">
	<div id="notify_<?= $pageData['id'] ?>">&nbsp;</div>
</div>

<div id="form-wrapper-<?= $pageData['id'] ?>" data-page-id="<?= $pageData['id'] ?>" ng-app="">
	<?=
	$form->open(
		[
			'method' => 'post',
			'role'   => 'form',
			'action' => '/pages/edit/'.$pageData['id'],
			'id'     => 'page_form_'.$pageData['id'],
		]
	); ?>
	<script src="/templates/admin/js/jquery.form.js"></script>
	<div class="col-xs-8">
		<?= $form->getElement('menu_name'); ?>
		<?= $form->getElement('title'); ?>
		<?= $form->getElement('url'); ?>
		<?= $form->getElement('content', ['id' => 'textarea_'.$pageData['id']]); ?>
	</div>
	<div class="col-xs-4">
		<span class="mTitleCounter" style="float:right;">Знаков: <?= strlen($form->getAttributes()['meta_title']) ?></span>
		<?= $form->getElement(
			'meta_title', [
				'onkeyup'         => 'getLen(this)',
				'data-len-target' => '.mTitleCounter',
			]
		); ?>
		<span class="mKeywordsCounter" style="float:right;">Знаков: <?= strlen($form->getAttributes()['meta_keywords']) ?></span>
		<?= $form->getElement(
			'meta_keywords', [
				'onkeyup'         => 'getLen(this)',
				'data-len-target' => '.mKeywordsCounter',
			]
		); ?>
		<span class="mDescriptionCounter" style="float:right;">Знаков: <?= strlen($form->getAttributes()['meta_description']) ?></span>
		<?= $form->getElement(
			'meta_description', [
				'onkeyup'         => 'getLen(this)',
				'data-len-target' => '.mDescriptionCounter',
			]
		); ?>
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
</div>
<script type="text/javascript" src="/templates/admin/js/ckeditor/ckeditor.js"></script>
<script>
	$(document).ready(function () {
		CKEDITOR.replace('textarea_<?=$pageData['id']?>');
		saveFormInit();
		notificationInit();
	});
	function notificationInit() {
		$('.notify-wrapper>#notify_<?=$pageData['id']?>').on('click', '.alert>button', function () {
			$('.notify-wrapper').css('display', 'none');
		});
	}
	function saveFormInit() {
		var options = {
			beforeSubmit: showRequest,
			success: showResponse
		};

		$('#page_form_<?=$pageData['id']?>').submit(function () {
			$(this).ajaxSubmit(options);
			return false;
		});
	}
	function showResponse(responseText, statusText, xhr, $form) {
		if (responseText == 'ok') {
			$('#notify_<?=$pageData['id']?>').html('<div class="alert alert-success"><button data-dismiss="alert" class="close close-sm" type="button">Закрыть <i class="fa fa-times"></i></button>Изменения сохранены</div>');
			$('.notify-wrapper').css('display', 'block');
		} else {
			$('#notify_<?=$pageData['id']?>').html('<div class="alert alert-danger"><button data-dismiss="alert" class="close close-sm" type="button">Закрыть <i class="fa fa-times"></i></button>Ошибка сохранения. Обратитесь к администратору сайта.</div>');
			$('.notify-wrapper').css('display', 'block');
		}
	}
	function showRequest(formData, jqForm, options) {
		var ckvalue = CKEDITOR.instances.textarea_<?=$pageData['id']?>.getData();
		for (var i = 0; i <= formData.length - 1; i++) {
			if (formData[i].name == 'editpages_<?=$pageData['id']?>[content]') {
				formData[i].value = ckvalue;
			}
		}
		return true;
	}
</script>
