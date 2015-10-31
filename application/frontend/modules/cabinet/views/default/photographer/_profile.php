<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 31.10.2015
 * Time: 17:49
 */
use domain\person\entities\Person;
use domain\studio\entities\Studio;

/**
 * @var Person    $person
 * @var Studio    $studio
 */
?>
<div class="col-xs-5">
    <h4>О себе</h4>
    <?= $person->about() ? $person->about() : 'пусто'; ?>
    <h4>E-mail</h4>
    <?= $person->email() ? $person->email() : 'пусто'; ?>
    <h4>Адрес</h4>
    <?= $person->address() ? $person->address() : 'пусто'; ?>
    <h4>Мобильный телефон</h4>
    <?= $person->mobPhone() ? $person->mobPhone() : 'пусто'; ?>
    <h4>Телефон</h4>
    <?= $person->phone() ? $person->phone() : 'пусто'; ?>
    <h4>Дата рождения</h4>
    <?= $person->dateBirth() ? \Yii::$app->formatter->asDate($person->dateBirth()) : 'пусто'; ?>
    <? if ($studio): ?>
        <h4>Студия</h4>
        <?= $studio->name(); ?>
    <? endif; ?>
</div>
