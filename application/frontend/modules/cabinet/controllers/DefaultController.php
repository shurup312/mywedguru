<?php
namespace cabinet\controllers;

use cabinet\controllers\defaults as defaults;
use domain\person\entities\Person;
use domain\person\values\UserType;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\components\PersonRepository;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

    public function actions($params = false)
    {
        /**
         * @var Person $person
         */
        $actions          = [];
        $actions['index'] = defaults\IndexAction::className();
        $actions['edit']  = defaults\EditAction::className();
        return $actions;
    }
}
