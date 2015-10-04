<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 19:16
 */
namespace cabinet\controllers\defaults;

use cabinet\controllers\photographer as photographer;
use cabinet\controllers\bride as bride;
use domain\person\entities\Person;
use domain\person\values\UserType;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\components\PersonRepository;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class IndexAction extends Action
{

    public function run($slug = false)
    {
        /**
         * @var Person $person
         */
        list($person, $slug) = $this->getPersonAndSlug($slug);
        $result = false;
        switch ($person->user()->type()->type()) {
            case UserType::USER_PHOTOGRAPGER:
                $result = (new photographer\IndexAction('index', $this->controller))->run($slug);
                break;
            case UserType::USER_BRIDE:
                $result = (new bride\IndexAction('index', $this->controller))->run($slug);
                break;
            default:
                \Yii::$app->getUser()->logout();
                \Yii::$app->response->redirect(URL::toRoute('/auth'));
                \Yii::$app->end();
        }
        return $result;
    }

    /**
     * @param $slug
     *
     * @return array
     * @throws NotFoundHttpException
     */
    private function getPersonAndSlug($slug)
    {
        if (!$slug) {
            /**
             * @var Person $person
             */
            $person = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
            $slug   = $person->user()->slug();
            return array($person, $slug);
        } else {
            $person = PersonRepository::getBySlug($slug);
            if (!$person) {
                throw new NotFoundHttpException('Пользователь не найден');
            }
            return array($person, $slug);
        }
}
}
