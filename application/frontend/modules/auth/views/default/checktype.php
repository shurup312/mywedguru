<?
use infrastructure\person\entities\User;
?>
Я регистрируюсь как:
<div class="container-fluid">
	<a class="btn btn-primary" href="/auth/step1/<?=User::USER_TYPE_BRIDE?>">Невеста</a>
	<a class="btn btn-primary" href="/auth/step1/<?=USer::USER_TYPE_PHOTOGRAPHER;?>">Фотограф</a>
</div>
