<?php
namespace app\modules\userDetails\controllers;

use frontend\models\UserType;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        switch (\Yii::$app->getUser()->identity->type) {
            case UserType::USER_BRIDE:
                \Yii::$app->response->redirect(URL::toRoute('bride/index'));
                \Yii::$app->end();
                break;
            case UserType::USER_PHOTOGRAPGER:
                \Yii::$app->response->redirect(URL::toRoute('photographer/index'));
                \Yii::$app->end();
                break;
            default:
                \Yii::$app->getUser()
                          ->logout();
                \Yii::$app->response->redirect(URL::toRoute('/'));
                \Yii::$app->end();
        }
    }
}
