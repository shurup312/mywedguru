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

/**
 * Class GetPhotographerAggregateService
 * @package app\modules\services
 *
 * @property User $user
 * @property PersonRepository $personRepository
 * @property StudioRepository $studioRepository
 */
class GetPhotographerAggregateService
{
    private $user;
    private $personRepository;
    private $studioRepository;

    public function __construct(User $user, PersonRepository $personRepository, StudioRepository $studioRepository)
    {
        if($user->type!=UserType::USER_PHOTOGRAPGER){
            throw new ServiceException('Переданный пользователь не является фотографом');
        }
        $this->personRepository = $personRepository;
        $this->studioRepository = $studioRepository;
        $this->user = $user;
    }

    /**
     * @return PhotographerAggregate
     */
    public function execute()
    {
        $person = $this->personRepository->getByUser($this->user);
        $studio = $this->studioRepository->getByPerson($person);
        return new PhotographerAggregate($person, $studio, null);
    }
}
