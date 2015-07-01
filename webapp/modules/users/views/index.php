<?php
/**
 * @var \webapp\modules\users_control\models\Users[] $list
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
<a href="/users/add/"><input type="button" class="btn btn-info" value="Добавить"/></a>
<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>Login</th>
		<th>E-Mail</th>
		<th>Rights</th>
		<th>Status</th>
		<th>Date_created</th>

	</tr>
	</thead>
	<tbody>
	<? foreach ($list as $column) {
		?>
		<tr>
		<td><?=$column['id'];?></td>
		<td><?=$column['login'];?></td>
		<td><?=$column['email'];?></td>
		<td><?=$column['rights'];?></td>
		<td><?=$column['status'];?></td>
		<td><?=$column['date_created'];?></td>

			<td class="listTable_actions">
				<a href="/users_control/edit/<?=$column['id'];?>"><input type="button" class="btn btn-sm btn-success" value="Изменить"/></a>
				<a href="/users_control/delete/<?=$column['id'];?>"><input type="button" class="btn btn-sm btn-danger" value="Удалить"/></a>
			</td>
		</tr>
		<?
	}?>
	</tbody>
</table>
