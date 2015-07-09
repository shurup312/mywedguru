<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 08.05.15
 * Time: 17:47
 */
use system\core\App;
use system\core\socials\FB;
use system\core\socials\OK;
use system\core\socials\VK;
use webapp\modules\adm\forms\LoginForm;

/**
 * @var LoginForm $loginForm
 */
$loginForm->setTemplate('{element}');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Вход в систему</title>
	<link rel="stylesheet" type="text/css" href="/public/components/users/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/public/components/users/css/bootstrap-reset.min.css">
	<link rel="stylesheet" type="text/css" href="/public/components/users/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/public/components/users/css/style.min.css">
	<link rel="stylesheet" type="text/css" href="/public/components/users/css/style-responsive.min.css">
	<script language="JavaScript" type="text/javascript" src="/public/components/users/js/jquery.min.js"></script>
</head>
<body>
<div class="container">
	<?= $loginForm->open(
		[
			'id'     => 'login',
			'class'  => 'form-signin',
			'method' => 'post',
		]
	); ?>
	<h2 class="form-signin-heading">Вход в систему</h2>

	<div class="login-wrap">
		<div class="user-login-info">
			<?= $loginForm->getElement(
				'email', [
						   'class'       => 'form-control black',
						   'id'          => 'email',
						   'placeholder' => 'E-mail',
					   ]
			); ?>
			<?= $loginForm->getElement(
				'pass', [
						  'class'       => 'form-control black',
						  'placeholder' => 'Пароль',
					  ]
			); ?>
		</div>
		<?= $loginForm->getElement(
			'submit', [
						'class' => 'btn btn-lg btn-login btn-block',
					]
		); ?>
	</div>
	<?= $loginForm->close(); ?>
	<a href="<?=VK::getURLForAuth(App::getConfig()['vkAPI']);?>">Войти через ВК</a>
	<a href="<?=OK::getURLForAuth(App::getConfig()['okAPI']);?>">Войти через OK</a>
		<a href="<?=FB::getURLForAuth(App::getConfig()['fbAPI']);?>">Войти через FB</a>
</body>
</html>
