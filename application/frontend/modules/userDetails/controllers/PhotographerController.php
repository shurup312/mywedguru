<?php
namespace userDetails\controllers;

use app\modules\userDetails\controllers\photographer\SkipStudioAction;
use infrastructure\person\components\PersonRepository;
use userDetails\controllers\photographer\IndexAction;
use domain\person\values\UserType;
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
                            $person = PersonRepository::getByUser(\Yii::$app->getUser()->identity);
                            return $person && $person->type()->type() == UserType::USER_PHOTOGRAPGER;
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
            'skip-studio' => SkipStudioAction::className(),
        ];
    }

}
