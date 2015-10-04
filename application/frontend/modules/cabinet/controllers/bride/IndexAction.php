<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 18:08
 */
namespace cabinet\controllers\bride;

use domain\person\entities\Person;
use domain\wedding\entities\Wedding;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\person\components\PersonRepository;
use infrastructure\wedding\components\WeddingRepository;
use yii\base\Action;

class IndexAction extends Action
{

    public function run($slug)
    {

        /**
         * @var Person  $bride
         * @var Wedding $wedding
         */
        $personBySlug = PersonRepository::getBySlug($slug);
        $bride        = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $wedding      = WeddingRepository::getByBride($personBySlug);
        $groom        = PersonRepository::getById($wedding->groomId());
        $isOwner = $bride->equalsTo($personBySlug);
        return $this->controller->render($bride->user()->type()->prefix().'/index', [
            'bride'   => $personBySlug,
            'wedding' => $wedding,
            'groom'   => $groom,
            'isOwner' => $isOwner,
        ]);
    }
}
