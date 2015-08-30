<?php
namespace app\modules\userDetails\controllers;

use frontend\models\UserType;
use yii\filters\AccessControl;
use yii\web\Controller;

class BrideController extends Controller
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
                            return \Yii::$app->getUser()->identity->type == UserType::USER_BRIDE;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
