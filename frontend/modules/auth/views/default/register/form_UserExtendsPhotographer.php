<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 09.07.2015
 * Time: 16:37
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $model
 */
?>
<a href="/auth/checktype">назад на выбор типа пользователя</a>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
