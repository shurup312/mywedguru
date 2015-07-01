<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 25.05.2015
 * Time: 14:32
 */

/**
 * @var $robots_text
 */
?>
<style type="text/css">
	.robots-input {
		width: 100%;
		height: 300px;
	}
	.robots-example p
	{
		font-weight: 600;
	}
</style>
<div class="row">
	<div class="col-lg-12">
		<p>
			<a href="/adm" class="btn btn-sm btn-info">панель управления</a>
		</p>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<div class="well">Файл <b>Robots.txt</b> — текстовый файл, расположенный на сайте, который предназначен для роботов поисковых систем. В этом файле
			веб-мастер может указать параметры индексирования своего сайта как для всех роботов сразу, так и для каждой поисковой системы по отдельности.
		</div>
		<form method="post" action="">
			<div>
				<textarea class="robots-input" name="text"><?= $robots_text ?></textarea>
			</div>
			<br/>
			<input type="submit" value="Отправить" name="submit" class="btn btn-sm btn-success"/>
		</form>

	</div>
	<div class="col-lg-6 robots-example">

		<p>Страндартный robots.txt</p>

		<div class="pageInfo">
			User-agent: Yandex<br/>
			Disallow: /login/<br/>
			Disallow: /reg_form/<br/>
			Host: <?= $_SERVER['SERVER_NAME'] ?><br/><br/>

			User-agent: *<br/>
			Disallow: /login/<br/>
			Disallow: /reg_form/
		</div>
		<br/>

		<p>Закрыть сайт от индиксации</p>

		<div class="pageInfo">
			User-agent: *<br/>
			Disallow: /
		</div>

	</div>
</div>
