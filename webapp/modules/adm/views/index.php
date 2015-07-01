<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 25.05.2015
 * Time: 14:01
 */

/**
 * @var $diskSpace
 * @var $freeSpace
 * @var $usedSpace
 * @var $memoryMax
 * @var $extensions
 */

?>
<div class="row">
	<div class="col-lg-12">
		<p>
			<a href="/adm/robots" class="btn btn-sm btn-info">robots.txt</a>
		</p>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
		<table class="table table-striped table-hover table-bordered">
			<tr>
				<td>Адресс сайта:</td>
				<td><?= $_SERVER['SERVER_NAME'] ?></td>
			</tr>
			<tr>
				<td>IP сайта:</td>
				<td><?= $_SERVER['SERVER_ADDR'] ?></td>
			</tr>
			<tr>
				<td>ТИЦ:</td>
				<td><?= $tic ?></td>
			</tr>
		</table>
	</div>
	<div class="col-lg-6">
		<div class="alert alert-warning ">
			<p><b>Используемые ресурсы:</b></p>

			<p>
				Доступный размер диска: <b><?= $diskSpace ?></b> Mb<br/>
				Доступное свободное место: <b><?= $freeSpace ?></b> Mb<br/>
				Использовано памяти в целом: <b><?= $usedSpace ?></b> Mb<br/>
				Максимальный пик использования памяти: <b><?= $memoryMax ?></b> Mb<br/>
			</p>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-6">
	</div>
	<div class="col-lg-6">
		<div class="alert alert-warning ">
			<p><b>Загруженные пакеты PHP:</b></p>

			<p>
				<?
				foreach ($extensions as $k => $v) {
					?><?= $v ?> | <?
				}
				?>
			</p>
		</div>
		<div class="alert alert-warning ">
			<p><b>PHP:</b></p>

			<p><?= PHP_VERSION; ?></p>
		</div>
	</div>
</div>
