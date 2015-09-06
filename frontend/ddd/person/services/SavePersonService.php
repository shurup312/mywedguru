<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.09.2015
 * Time: 19:18
 */
namespace app\ddd\person\services;

use app\ddd\exceptions\exceptions\ServiceException;
use frontend\ddd\person\repositories\PersonRepository;
use frontend\models\Person;

class SavePersonService
{

    private $person;
    private $repository;

    /**
     * @param Person           $aPerson
     * @param PersonRepository $aPersonRepository
     */
    public function __construct(Person $aPerson, PersonRepository $aPersonRepository)
    {
        $this->person     = $aPerson;
        $this->repository = $aPersonRepository;
    }

    /**
     * @param $aData
     *
     * @return bool
     * @throws ServiceException
     */
    public function execute($aData)
    {
        $this->person->setFirstName($aData['firstName']);
        $this->person->setLastName($aData['lastName']);
        $this->person->setPhone($aData['phone']);
        $this->person->setMobPhone($aData['mobPhone']);
        $this->person->setAddress($aData['address']);
        $this->person->setEmail($aData['email']);
        $this->person->setDateBirth(new \DateTime($aData['dateBirth']));
        $this->person->setAbout($aData['about']);
        return $this->repository->save($this->person);
    }
}
