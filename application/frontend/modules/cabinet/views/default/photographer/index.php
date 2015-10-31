<?php
/**
 * User: Oleg Prihodko
 * Mail: shurup@e-mind.ru
 * Date: 27.08.2015
 * Time: 11:53
 */
use domain\person\entities\Person;
use domain\service\entities\Service;
use domain\studio\entities\Studio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var Person     $person
 * @var Studio     $studio
 * @var boolean    $isOwner
 * @var Service[]  $serviceList
 * @var array      $hoursArray
 * @var yii\base\Controller $controller
 */
?>
<?= $person->user()->type()->name(); ?>
<h3>
    <?= $person->lastName(); ?> <?= $person->firstName(); ?>
    <?php
    if ($isOwner) {
        echo HTML::a('Именить личные данные', [URL::toRoute('edit')], ['class' => 'btn btn-info']);
    }
    ?>
</h3>

<?= $this->render('_profile', ['person' => $person, 'studio' => $studio]) ?>

<?php Pjax::begin(['enablePushState' => false]); ?>
<?= (new \cabinet\controllers\photographer\SavePriceAction('save-price', $controller))->run(); ?>
<?php Pjax::end(); ?>
