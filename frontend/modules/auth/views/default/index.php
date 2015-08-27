<?
use frontend\modules\socials\FB;
use frontend\modules\socials\OK;
use frontend\modules\socials\VK;
?>
<a class="btn btn-primary" href="<?= VK::getURLForAuth(\yii::$app->params['vkAPI']);?>">Войти через ВК</a>
<a class="btn btn-primary" href="<?=OK::getURLForAuth(\yii::$app->params['okAPI']);?>">Войти через OK</a>
<a class="btn btn-primary" href="<?=FB::getURLForAuth(\yii::$app->params['fbAPI']);?>">Войти через FB</a>
