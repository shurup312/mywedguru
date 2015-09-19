<?php
namespace userDetails\controllers;

use app\modules\userDetails\controllers\bride\IndexAction;
use domain\person\values\UserType;
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

    public function actions()
    {
        return [
            'index'       => IndexAction::className(),
        ];
    }
}
