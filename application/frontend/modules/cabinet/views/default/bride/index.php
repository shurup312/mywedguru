<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 27.08.2015
 * Time: 11:53
 */
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \frontend\models\UserExtendsBride $userExtendModel
 */
?>
<h3><?= $userExtendModel->last_name; ?> <?= $userExtendModel->first_name; ?></h3>
<?=HTML::a('Именить личные данные',[URL::toRoute('edit')],['class'=>'btn btn-info pull-right']);?>
<h4>Жених</h4>
<?= $userExtendModel->fiance_first_name ?> <?=$userExtendModel->fiance_last_name;?>
<h4><?=$userExtendModel->attributeLabels()['date_wedding'];?></h4>
<?= $userExtendModel->date_wedding?\Yii::$app->formatter->asDate($userExtendModel->date_wedding):'пусто'; ?>
