<?php
namespace cabinet\controllers;

use cabinet\controllers\photographer as photographer;
use cabinet\controllers\bride as bride;
use cabinet\controllers\defaults as defaults;
use domain\person\entities\Person;
use domain\person\values\UserType;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
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

    public function actions()
    {
        /**
         * @var Person $person
         */
        $person  = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $actions = [];
        switch ($person->type()->type()) {
            case UserType::USER_PHOTOGRAPGER:
                $actions['index'] = photographer\IndexAction::className();
                break;
            case UserType::USER_BRIDE:
                $actions['index'] = bride\IndexAction::className();
                break;
            default:
                \Yii::$app->getUser()->logout();
                \Yii::$app->response->redirect(URL::toRoute('/auth'));
                \Yii::$app->end();
        }
        $actions['edit'] = defaults\EditAction::className();
        return $actions;
    }
}
