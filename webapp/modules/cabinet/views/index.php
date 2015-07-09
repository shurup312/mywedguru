<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 04.07.2015
 * Time: 18:44
 */
use system\core\App;

/**
 * @var \webapp\modules\cabinet\models\UserExtend $user
 * @var \webapp\modules\cabinet\models\UserExtendHistory|null $existModerate
 */
?>
<?
if($existModerate){
	?><div class="alert alert-info">
		Вы отправили на модерацию данные по изменению аккаунта. Как только отправленые Вами данные будут одобрены,
		изменения отразятся в личном кабинете.
	</div><?
}
?>
Главная / Личный кабинет
<h3><?= $user->last_name.' '.$user->first_name; ?></h3>
<? if($user->avatar){
	?><img src="/public/components/cabinet/<?=$user->avatar;?>" alt="<?=$user->first_name;?>" /><br><?
}
?>

<h4>Контактный данные:</h4>
Телефон : <?=$user->phone;?><br>
Рабочий телефон: <?=$user->work_phone;?><br>
E-mail: <?=App::get('user')->email;?>

<h4>Персональные данные</h4>
Паспорт серия, номер: <?=$user->passport;?><br>
Кем и когда выдан: <?=$user->passport_ext;?>
<br>
<a href="/cabinet/edit" class="btn btn-primary">Изменение</a>
