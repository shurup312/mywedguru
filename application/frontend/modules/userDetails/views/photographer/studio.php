<?
/**
 * @var StudioForm $studioForm
 */
use userDetails\forms\StudioForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'role' => 'form'],
]); ?>
<?= $form->field($studioForm, 'name')
         ->textInput() ?>
<?= $form->field($studioForm, 'phone')
         ->textInput() ?>
<?= $form->field($studioForm, 'address')
         ->textInput() ?>
<div class="form-group">
    <?= Html::submitButton('Создать студию', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Пропустить', 'skip-studio', ['class' => 'btn btn-primary']) ?>
</div>
<? ActiveForm::end(); ?>
