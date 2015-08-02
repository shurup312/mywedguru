<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 02.08.2015
 * Time: 19:13
 */
/**
 * @var boolean  $existModerate
 * @var \webapp\modules\cabinet\models\UserExtendBride $user
 */
if($existModerate){
	?><div class="alert alert-info">
		Вы отправили на модерацию данные по изменению аккаунта. Как только отправленые Вами данные будут одобрены,
		изменения отразятся в личном кабинете.
	</div><?
}
?>
Главная / Личный кабинет

<a href="/cabinet/edit/" class="btn btn-primary">Изменить данные</a>
<h4><?=$user->first_name;?> <?=$user->last_name;?></h4>
<table class="table">
	<tr>
		<td>Жених</td>
		<td><?=$user->fiance_last_name;?> <?=$user->fiance_first_name;?></td>
	</tr>
	<tr>
		<td>Дата свадьбы</td>
		<td><?=$user->date_wedding;?></td>
	</tr>
	<tr>
		<td>Аватарка</td>
		<td><?=$user->avatar;?></td>
	</tr>
</table>
