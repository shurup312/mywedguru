<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 19.09.2015
 * Time: 14:14
 */
/**
 * @var WeddingForm $weddingForm
 */
use userDetails\forms\WeddingForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'role' => 'form'],
]); ?>
<?= $form->field($weddingForm, 'groomFirstName')
         ->textInput() ?>
<?= $form->field($weddingForm, 'groomLastName')
         ->textInput() ?>
<?= $form->field($weddingForm, 'date')->widget(\yii\jui\DatePicker::classname(), [
    'inline'        => true,
    'language'      => 'ru',
    'dateFormat'    => 'dd.MM.yyyy',
    'class'         => 'form-control',
    'clientOptions' => [
        'yearRange'   => date('Y').':'.date('Y',strtotime('+3years')),
        'changeMonth' => true,
        'changeYear'  => true,
        'defaultDate' => '01.01.1980',
        'minDate'     => date('d.m.Y'),
    ],
]) ?>
<div class="form-group">
    <?= Html::submitButton('Сохранить данные свадьбы', ['class' => 'btn btn-success']) ?>
</div>
<? ActiveForm::end(); ?>
