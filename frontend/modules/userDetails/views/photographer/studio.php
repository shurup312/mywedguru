<?
/**
 * @var StudioForm $studioForm
 */
use app\modules\userDetails\forms\StudioForm;
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
    <?= Html::a('Пропустить', '/cabinet', ['class' => 'btn btn-primary']) ?>
</div>
<? ActiveForm::end(); ?>
