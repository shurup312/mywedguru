<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 02.07.2015
 * Time: 17:41
 */
/**
 * @var \webapp\modules\cabinet\models\UserExtendHistory::[] $list
 */
foreach ($list as $item) {
	?>
	<div class="col-lg-5 alert alert-info col-xs-12">
		<div>
			<div class="bold col-xs-12">
				<div class="col-xs-4">Параметр</div>
				<div class="col-xs-4">Новое значение</div>
				<div class="col-xs-4">Старое значение</div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Фамилия</div>
				<div class="col-xs-4"><?= $item['new_first_name']; ?></div>
				<div class="col-xs-4"><?= $item['old_first_name']; ?></div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Имя</div>
				<div class="col-xs-4"><?= $item['new_last_name']; ?></div>
				<div class="col-xs-4"><?= $item['old_last_name']; ?></div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Телефон</div>
				<div class="col-xs-4"><?= $item['new_phone']; ?></div>
				<div class="col-xs-4"><?= $item['old_phone']; ?></div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Рабочий телефон</div>
				<div class="col-xs-4"><?= $item['new_work_phone']; ?></div>
				<div class="col-xs-4"><?= $item['old_work_phone']; ?></div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Паспорт</div>
				<div class="col-xs-4"><?= $item['new_passport']; ?></div>
				<div class="col-xs-4"><?= $item['old_passport']; ?></div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Кем и когда выдан</div>
				<div class="col-xs-4"><?= $item['new_passport_ext']; ?></div>
				<div class="col-xs-4"><?= $item['old_passport_ext']; ?></div>
			</div>
			<div class="col-xs-12">
				<div class="col-xs-4">Аватар</div>
				<div class="col-xs-4">
					<? if ($item['new_avatar']) {
						?><img src="/public/components/cabinet/<?= $item['new_avatar']; ?>" alt="" class="col-xs-12"><?
					} else if ($item['old_avatar']) {
						?><img src="/public/components/cabinet/<?= $item['old_avatar']; ?>" alt="" class="col-xs-12"><?
					} ?>

				</div>
				<div class="col-xs-4">
					<? if ($item['old_avatar']) {
						?><img src="/public/components/cabinet/<?= $item['old_avatar']; ?>" alt="" class="col-xs-12"><?
					} ?>
				</div>
			</div>
		</div>
		<div class="col-xs-8">
			<br>
			<a href="/cabinet/approve/<?=$item['id'];?>"><input type="button" class="btn btn-success" value="Принять"/></a>
			<a href="/cabinet/reject/<?=$item['id'];?>"><input type="button" class="btn btn-warning" value="Отменить"/></a>
		</div>
	</div>
	<div class="col-lg-1 col-xs-0"></div>
	<?
}
?>
<style type="text/css">
	.bold {
		font-weight: bold;
	}
</style>
