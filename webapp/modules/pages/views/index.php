<?php
/**
 * @var \webapp\modules\pages\models\Pages[] $list
 */

?>
<style type="text/css">
	.listTable_actions > a {
		margin-right: 1ex;
	}

	.vis i {
		font-size: 14px;
	}

	.vis i.visible {
		color: #008000;
	}

	.vis i.not-visible {
		color: lightcoral;
	}

	.pages-list .page-data:hover {
		cursor: pointer;
	}

	tr.selected {
		background-color: #5BC0DE;
	}
	.close-tab
	{
		float: left;
		position: absolute;
		color: red;
		right: 0;
		top: 0;
	}
	.close-tab:hover, .close-tab:active
	{
		color: red;
	}
	.page-view
	{
		padding: 10px;
		background-color: rgb(221, 221, 221);
	}
	.page-view .panel-body
	{
		background-color: #fff;
	}
</style>
<script src="/vendor/angular/angular.min.js"></script>
<script src="/templates/admin/js/app/pages/controllers.js"></script>

<script>
	var pagesJSON = <?=json_encode($list)?>;
</script>
<a href="/pages/add/" class="btn btn-sm btn-primary"><i class="fa fa-plus"> Добавить страницу</i></a>
<div class="row" ng-app="pagesApp">
	<div class="col-lg-4" ng-controller="pageListController" ng-init="init()">
		Поиск: <input ng-model="query" class="form-control">
		<table class="table pages-list">
			<thead>
			<tr>
				<th class="col-xs-1">Видимость</th>
				<th>Название</th>
				<th class="col-xs-1">Ссылка</th>
			</tr>
			</thead>
			<tbody>
			<tr class="page-data" ng-repeat="(pageIndex, page) in pages | filter:query" data-item-id="{{page.id}}" ng-click="selectRow(pageIndex, page.id)" ng-class="selected==page.id?'selected':''">
				<td class="vis" data-item-id="{{page.id}}">
					<button class="btn btn-xs"><i class="fa" ng-class="page.is_visible==0?'fa-eye-slash not-visible':'fa-eye visible'"></i></button>
				</td>
				<td>{{page.title}}</td>
				<td><a href="/{{page.url}}" target="_blank">{{page.url}}</a></td>
				<td class="listTable_actions">
					<button class="btn btn-xs btn-danger" ng-click="deletePage(pageIndex, page.id)"><i class="fa fa-trash-o"></i></button>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="col-lg-8">
		<section class="panel page-view">
			<header class="panel-heading tab-bg-dark-navy-blue" hidden="">
				<ul class="nav nav-tabs" id="pages-tabs">

				</ul>
			</header>
			<div class="panel-body">
				<div class="tab-content" id="tab-content-wrapper">
					<h4 id="tab-tooltip">Выберите страницу в списке слева</h4>
				</div>
			</div>
		</section>
	</div>
</div>
