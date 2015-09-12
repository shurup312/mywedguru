<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 27.08.2015
 * Time: 11:53
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \frontend\models\UserExtendsPhotographer $userExtendModel
 */
?>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'role' => 'form'],
]); ?>
<?= $form->field($userExtendModel, 'first_name')
         ->textInput() ?>
<?= $form->field($userExtendModel, 'last_name')
         ->textInput() ?>
<?= $form->field($userExtendModel, 'fiance_first_name')
         ->textInput() ?>
<?= $form->field($userExtendModel, 'fiance_last_name')
         ->textInput() ?>
<?= $form->field($userExtendModel, 'date_birth')
         ->widget(\yii\jui\DatePicker::classname(), [
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
<?= $form->field($userExtendModel, 'date_wedding')
         ->widget(\yii\jui\DatePicker::classname(), [
             'inline'        => true,
             'language'      => 'ru',
             'dateFormat'    => 'dd.MM.yyyy',
             'clientOptions' => [
                 'changeMonth' => true,
                 'changeYear'  => true,
                 'minDate'     => date('d.m.Y'),
             ],
         ]) ?>
<div class="form-group">
    <?= Html::submitButton('Обновить данные', ['class' => 'btn btn-success']) ?>
</div>
<? ActiveForm::end(); ?>

