<?php
namespace app\modules\userDetails\controllers;

use app\modules\services\GetPhotographerAggregateService;
use frontend\models\UserType;
use yii\filters\AccessControl;
use yii\web\Controller;

class PhotographerController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => true,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->getUser()->identity->type==UserType::USER_PHOTOGRAPGER;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $service      = new GetPhotographerAggregateService(\Yii::$app->getUser()->identity);
        $photographer = $service->execute();
        if ($photographer->studioAggregate()!==null) {
            \Yii::$app->response->redirect('/photographer');
            \Yii::$app->end();
        }
        /**
         * TODO: сделать форму для студии
         */
        return $this->render('studio', [
            'studio' => null,
        ]);
    }
}
