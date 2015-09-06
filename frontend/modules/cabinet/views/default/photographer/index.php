<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 27.08.2015
 * Time: 11:53
 */
use frontend\models\Person;
use frontend\models\Studio;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var Person $person
 * @var Studio $studio
 */
?>
<h3><?= $person->lastName(); ?> <?= $person->firstName(); ?></h3>
<?=HTML::a('Именить личные данные',[URL::toRoute('edit')],['class'=>'btn btn-info pull-right']);?>
<h4>О себе</h4>
<?= $person->about()?$person->about():'пусто'; ?>
<h4>E-mail</h4>
<?= $person->email()?$person->email():'пусто'; ?>
<h4>Адрес</h4>
<?= $person->address()?$person->address():'пусто'; ?>
<h4>Мобильный телефон</h4>
<?= $person->mobPhone()?$person->mobPhone():'пусто'; ?>
<h4>Телефон</h4>
<?= $person->phone()?$person->phone():'пусто'; ?>
<h4>Дата рождения</h4>
<?= $person->dateBirth()?\Yii::$app->formatter->asDate($person->dateBirth()):'пусто'; ?>
<? if($studio): ?>
    <h4>Студия</h4>
    <?= $studio->name(); ?>
<? endif ;?>
