<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 02.08.2015
 * Time: 19:13
 */
/**
 * @var boolean $existModerate
 * @var \webapp\modules\cabinet\models\UserExtendPhotographer $user
 */
if($existModerate){
	?><div class="alert alert-info">
		Вы отправили на модерацию данные по изменению аккаунта. Как только отправленые Вами данные будут одобрены,
		изменения отразятся в личном кабинете.
	</div><?
}
?>
<a href="/cabinet/edit/" class="btn btn-primary">Изменить данные</a>
<h4><?=$user->first_name;?> <?=$user->last_name;?></h4>
<table class="table">
	<tr>
		<td>Название студии</td>
		<td><?=$user->studio_name;?></td>
	</tr>
	<tr>
		<td>Сайт</td>
		<td><?=$user->site_name;?></td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td><?=$user->email;?></td>
	</tr>
	<tr>
		<td>Телефон</td>
		<td><?=$user->phone;?></td>
	</tr>
	<tr>
		<td>Аватарка</td>
		<td><?=$user->avatar;?></td>
	</tr>
</table>
