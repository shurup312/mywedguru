<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 31.10.2015
 * Time: 17:50
 */
use domain\price\entities\PersonService;
use domain\service\entities\Service;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var Service[]       $serviceList
 * @var PersonService[] $personServiceList
 * @var array           $hoursArray
 * @var bool            $isSaved
 */
?>
<!-- price -->
<div class="col-xs-7">
    <div class="alert alert-info">
        <?php $form = ActiveForm::begin([
            'action'  => URL::toRoute('save-price'),
            'options' => ['enctype' => 'multipart/form-data', 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'data-pjax' => 1],
            'id'      => 'services'
        ]); ?>
        <div class="container-fluid">
            <? foreach ($serviceList as $service): ?>
                <div class="col-xs-4">
                    <label for="service[<?= $service->id(); ?>]"><?= $service->name(); ?></label>
                </div>
                <div class="col-xs-2">
                    <?= Html::dropDownList('hours['.$service->id().']',
                        isset($personServiceList[$service->id()]) ? $personServiceList[$service->id()]->hours() : null, $hoursArray,
                        ['id' => 'hours['.$service->id().']', 'class' => 'form-control']); ?>
                </div>
                <div class="col-xs-6">
                    <?= Html::textInput('cost['.$service->id().']',
                        isset($personServiceList[$service->id()]) ? $personServiceList[$service->id()]->cost() : null,
                        ['placeholder' => 'Цена в рублях', 'type' => 'number', 'class' => 'form-control']); ?>
                </div>
            <? endforeach; ?>
            <div class="col-xs-12">
                <?= Html::submitButton('Обновить данные', ['class' => 'btn btn-success']) ?>
                <? if($isSaved){
                    ?>Данные успешно сохранены.<?
                };?>
            </div>
        </div<
        <? ActiveForm::end(); ?>
    </div>
</div>

<!-- END price -->
