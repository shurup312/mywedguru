<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 08.05.15
 * Time: 20:48
 */
/**
 * @var array $listBlocks
 */

?>
<style type="text/css">
	.listTable_actions {
		width: 35ex;
	}
	.listTable_actions>a {
		margin-right: 1ex;
	}
</style>
<div class="alert alert-info">
	Тестовые блоки представляют собой размещенные на сайте тексты, которые можно отредактировать.
</div>
<table class="table">
	<thead>
	<tr>
		<th>Название</th>
		<th>Действия</th>
	</tr>
	</thead>
	<tbody>
	<? foreach ($listBlocks as $news) {
		?>
		<tr>
			<td><?=$news['title'];?></td>
			<td class="listTable_actions">
				<a href="/blocks/edit/<?=$news['id'];?>"><input type="button" class="btn btn-sm btn-success" value="Изменить"/></a>
			</td>
		</tr>
		<?
	}?>

	</tbody>
</table>
