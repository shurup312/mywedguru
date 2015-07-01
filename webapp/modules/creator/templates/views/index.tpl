<?php
/**
 * @var \webapp\modules\{ModuleName}\models\{ModelName}[] $list
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
<a href="/{ModuleName}/add/"><input type="button" class="btn btn-info" value="Добавить"/></a>
<table class="table">
	<thead>
	<tr>
{columnsLabels}
	</tr>
	</thead>
	<tbody>
	<? foreach ($list as $column) {
		?>
		<tr>
{columnsNames}
			<td class="listTable_actions">
				<a href="/{ModuleName}/edit/<?=$column['id'];?>"><input type="button" class="btn btn-success" value="Изменить"/></a>
				<a href="/{ModuleName}/delete/<?=$column['id'];?>"><input type="button" class="btn btn-danger" value="Удалить"/></a>
			</td>
		</tr>
		<?
	}?>
	</tbody>
</table>
