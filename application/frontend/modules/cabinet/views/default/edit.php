<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 27.08.2015
 * Time: 11:53
 */
use cabinet\forms\photographer\PersonForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var PersonForm $model
 */
?>
<? if(\Yii::$app->session->hasFlash('notice')): ?>
    <?=HTML::tag('div',\Yii::$app->session->getFlash('notice',['alert alert-warning']));?>
<? endif ;?>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'role' => 'form'],
]); ?>
<?= $form->field($model, 'firstName')->textInput() ?>
<?= $form->field($model, 'lastName')->textInput() ?>
<?= $form->field($model, 'phone')->textInput() ?>
<?= $form->field($model, 'mobPhone')->textInput() ?>
<?= $form->field($model, 'address')->textInput() ?>
<?= $form->field($model, 'email')->textInput() ?>
<?= $form->field($model, 'dateBirth')->widget(\yii\jui\DatePicker::classname(), [
    'inline'        => true,
    'language'      => 'ru',
    'dateFormat'    => 'dd.MM.yyyy',
    'class'         => 'form-control',
    'clientOptions' => [
        'yearRange'   => '1960:2030',
        'changeMonth' => true,
        'changeYear'  => true,
        'defaultDate' => '01.01.1980',
        'minDate'     => date('d.m.Y', strtotime('-60 years')),
    ],
]) ?>
<?= $form->field($model, 'about')->textarea() ?>
<div class="form-group">
    <?= Html::submitButton('Обновить данные', ['class' => 'btn btn-success']) ?>
</div>
<? ActiveForm::end(); ?>

