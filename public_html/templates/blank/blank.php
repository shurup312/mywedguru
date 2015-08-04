<?
/**
 * @var string $title
 * @var string $content
 */
use system\core\App;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?=App::html()->css;?>
	<title></title>

</head>

<body>
<a href="/auth/logout/">Выйти</a>
<?= $content ?>
<?=App::html()->jsload;?>
</body>
</html>
