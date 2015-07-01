<?php
/**
 * User: FomalhautRed
 * Mail: demirurg@gmail.com
 * Date: 24.03.2015
 * Time: 9:27
 */

?>
<div ng-app="changePassApp" ng-controller="changePasswordCtrl">
	<div class="col-sm-4" style="padding-left: 0">
		<div class="widget">
			<div class="widget-body" style="padding: 10px">
				<? if (!isset($ok)): ?>
					<form name="change-password" method="post" action="password">
						<? if (@$message): ?>
							<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-danger"><?= @$message ?></div>
								</div>
							</div>
						<? endif ?>
						<div class="row">
							<div class="col-lg-4">Текущий пароль:</div>
							<div class="col-lg-8">
								<input class="form-control" type="password" name="old_password">
							</div>
						</div>
						<div style="height: 20px"></div>
						<div class="row">
							<div class="col-lg-4">Новый пароль:</div>
							<div class="col-lg-8">
								<input class="form-control" type="password" name="new_password"
									   placeholder="придумайте новый пароль" ng-model="pass1" required="">
							</div>
						</div>
						<div style="height: 20px"></div>
						<div class="row">
							<div class="col-lg-4">Ещё раз:</div>
							<div class="col-lg-8">
								<input class="form-control" type="password" name="new_password2"
									   placeholder="введите его ещё раз" ng-model="pass2" required="">
							</div>
						</div>
						<div style="height: 20px"></div>
						<div class="row">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<div class="alert alert-success" ng-show="pass1==pass2 && pass2" style="display: none">Пароли совпадают</div>
								<div class="alert alert-danger" ng-show="pass1!=pass2 && pass2" style="display: none">Пароли не совпадают</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4"></div>
							<div class="col-lg-8">
								<input class="btn btn-success" type="submit" value="Сохранить" ng-disabled="pass2!=pass1">
							</div>
						</div>
					</form>
				<? else: ?>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success">Пароль успешно изменён!</div>
							<a class="btn btn-primary" href="/realty">Продолжить</a>
						</div>
					</div>
				<?endif ?>
			</div>
		</div>
	</div>
	<div class="col-lg-4"></div>
</div>
