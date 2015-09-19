<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 27.08.2015
 * Time: 11:53
 */
use domain\person\entities\Person;
use domain\wedding\entities\Wedding;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var Person $bride
 * @var Person $groom
 * @var Wedding $wedding
 */
?>
<h3><?= $bride->lastName(); ?> <?= $bride->firstName(); ?></h3>
<?=HTML::a('Именить личные данные',[URL::toRoute('edit')],['class'=>'btn btn-info pull-right']);?>
<h4>Жених</h4>
<?= $groom->lastName(); ?> <?=$groom->firstName();?>
<h4>О себе</h4>
<?= $bride->about()?$bride->about():'пусто'; ?>
<h4>E-mail</h4>
<?= $bride->email()?$bride->email():'пусто'; ?>
<h4>Адрес</h4>
<?= $bride->address()?$bride->address():'пусто'; ?>
<h4>Мобильный телефон</h4>
<?= $bride->mobPhone()?$bride->mobPhone():'пусто'; ?>
<h4>Телефон</h4>
<?= $bride->phone()?$bride->phone():'пусто'; ?>
<h4>Дата рождения</h4>
<?= $bride->dateBirth()?\Yii::$app->formatter->asDate($bride->dateBirth()):'пусто'; ?>

