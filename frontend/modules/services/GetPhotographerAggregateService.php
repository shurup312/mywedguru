<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 22:13
 */
namespace app\modules\services;

use app\modules\aggregates\PhotographerAggregate;
use app\modules\exceptions\ServiceException;
use app\modules\repositories\PersonRepository;
use app\modules\repositories\StudioRepository;
use frontend\models\User;
use frontend\models\UserType;

class GetPhotographerAggregateService
{
    private $user;
    public function __construct(User $user)
    {
        if($user->type!=UserType::USER_PHOTOGRAPGER){
            throw new ServiceException('Переданный пользователь не является фотографом');
        }
        $this->user = $user;
    }

    /**
     * @return PhotographerAggregate
     */
    public function execute()
    {
        $person = PersonRepository::getByUserId($this->user->id);
        $studio = StudioRepository::getByPerson($person);
        return new PhotographerAggregate($person, $studio, null);
    }
}
