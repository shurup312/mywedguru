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
 * @var Person $personModel
 */
?>
<h3><?= $userExtendModel->last_name; ?> <?= $userExtendModel->first_name; ?></h3>
<?=HTML::a('Именить личные данные',[URL::toRoute('edit')],['class'=>'btn btn-info pull-right']);?>
<h4><?=$userExtendModel->attributeLabels()['about'];?></h4>
<?= $userExtendModel->about?$userExtendModel->about:'пусто'; ?>
<h4><?=$userExtendModel->attributeLabels()['phone'];?></h4>
<?= $userExtendModel->phone?$userExtendModel->phone:'пусто'; ?>
<h4><?=$userExtendModel->attributeLabels()['date_birth'];?></h4>
<?= $userExtendModel->date_birth?\Yii::$app->formatter->asDate($userExtendModel->date_birth):'пусто'; ?>
<h4><?=$userExtendModel->attributeLabels()['studio_name'];?></h4>
<?= $userExtendModel->studio_name?$userExtendModel->studio_name:'пусто'; ?>
<h4><?=$userExtendModel->attributeLabels()['site_name'];?></h4>
<?= $userExtendModel->site_name?$userExtendModel->site_name:'пусто'; ?>
