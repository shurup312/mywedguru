<?php
namespace cabinet\controllers;

use app\ddd\exceptions\exceptions\ServiceException;
use app\modules\cabinet\controllers\defaults\EditAction;
use cabinet\controllers\defaults\IndexAction;
use infrastructure\common\exceptions\EntityException;
use app\ddd\person\services\GetPersonService;
use app\ddd\person\services\SavePersonService;
use app\ddd\studio\repositories\StudioRepository;
use app\modules\cabinet\forms\photographer\PersonForm;
use frontend\ddd\person\repositories\PersonRepository;
use frontend\models\User;
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
        return [
            'index' => IndexAction::className(),
            'edit' => EditAction::className(),
        ];
    }


}
