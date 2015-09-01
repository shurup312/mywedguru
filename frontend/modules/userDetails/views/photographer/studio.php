<?
/**
 * @var Studio $studio
 */
?>
<?php use app\modules\entities\Studio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'role' => 'form'],
]); ?>
<?= $form->field($studio, 'name')
         ->textInput() ?>
<?= $form->field($studio, 'phone')
         ->textInput() ?>
<?= $form->field($studio, 'address')
         ->textInput() ?>
<div class="form-group">
    <?= Html::submitButton('Создать группу', ['class' => 'btn btn-success']) ?>
</div>
<? ActiveForm::end(); ?>
