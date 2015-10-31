<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\price\commands;

use domain\person\entities\Person;
use domain\service\contracts\ServiceRepositoryException;
use domain\service\entities\Service;
use infrastructure\service\components\ServiceRepository;

class SavePersonServiceCommand
{

    private $person;
    private $service;
    private $hours;
    private $cost;

    /**
     * @param Person  $aPerson
     * @param         $aServiceId
     * @param         $aHours
     * @param         $aCost
     *
     * @throws ServiceRepositoryException
     */
    public function __construct(Person $aPerson, $aServiceId, $aHours, $aCost)
    {
        $this->person  = $aPerson;
        $service = ServiceRepository::getByID($aServiceId);
        if(!$service){
            throw new ServiceRepositoryException();
        }
        $this->service = $service;
        $this->hours   = $aHours;
        $this->cost    = $aCost;
    }

    /**
     * @return Person
     */
    public function person()
    {
        return $this->person;
    }

    /**
     * @return Service
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * @return integer
     */
    public function hours()
    {
        return $this->hours;
    }

    /**
     * @return integer
     */
    public function cost()
    {
        return $this->cost;
    }
}
