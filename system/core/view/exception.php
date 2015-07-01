<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 20.04.15
 * Time: 12:47
 */
/**
 * @var Exception $exception
 * @var array     $errorFile
 * @var array     $errorStack
 */
?>
<style type="text/css">
	pre {
		margin: 0;
	}

	.code-first {
	}

	.code-seconds {
		margin-left: 30px;
	}

	.code-seconds .code-line {
		/*display: none;*/
	}
	.code-seconds .code-info {
		padding: 0 8px;
		margin-top: 20px;
		background: #fdd;
		font-weight: bold;
	}

	.code-line {
		padding: 4px 8px;
		background: #ddd;
	}

	.code-errorLine {
		background: #bbb;
	}
	.openPlus {
		cursor: pointer;
		border: 1px solid black;
		width:22px;
		height: 22px;
		box-sizing: border-box;
		display: inline-block;
		text-align: center;
	}
</style>
<h3>
	<?= $exception->getMessage(); ?> (код: <?= $exception->getCode(); ?>)</h3>
<h4>Ошибка в файле <?= $exception->getFile(); ?> (строка: <?= $exception->getLine(); ?>)</h4>
<div class="code code-first">
	<?php
	echo '<pre>';
	foreach ($errorFile as $line => $code) {
		echo '<div class="code-line '.($line==$exception->getLine()?' code-errorLine':'').'">'.$line.' '.$code.'</div>';
	}
	echo '</pre>';
	?>
</div>
<h4>Стэк:</h4>
<? foreach ($errorStack as $name => $info) {
	?>
	<div class="code code-seconds">
		<div class="code-info"><span class="openPlus">+</span> Проход по файлу <?= $info['file']; ?> (строка: <?= $info['line']; ?>)</div>
		<?php
		echo '<pre>';
		foreach ($info['code'] as $line => $code) {
			echo '<div class="code-line '.($line==$info['line']?' code-errorLine':'').'">'.$line.' '.htmlspecialchars($code).'</div>';
		}
		echo '</pre>';
		?>
	</div>
<?
}
?>
<script type="text/javascript" src="/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">

	var contentBlock = {
		'+':'-',
		'-':'+'
	};

	$('.code-seconds>.code-info>span').on('click', function(){
		var newContent = contentBlock[$(this).html()];
		$(this).html(newContent);
		$(this).closest('.code').find('.code-line').toggle();
	});
</script>
