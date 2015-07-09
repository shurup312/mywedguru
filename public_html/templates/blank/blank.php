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
	<title><?= $title ?></title>

</head>

<body>
<?= $content ?>
<?=App::html()->jsload;?>
</body>
</html>
