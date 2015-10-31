<?php
namespace domain\price\entities;

use domain\common\components\Entity;
use infrastructure\person\entities\User;

/**
 * Class Person
 *
 * @package domain\person\entities
 *
 * @property User $user
 */
class PersonService extends Entity
{
    protected $id;
    protected $personId;
    protected $serviceId;
    protected $hours;
    protected $cost;

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param $aId
     */
    public function setId($aId)
    {
        $this->id = $aId;
    }
    /**
     * @return integer
     */
    public function personId()
    {
        return $this->personId;
    }

    /**
     * @param $aPersonId
     */
    public function setPersonId($aPersonId)
    {
        $this->personId = $aPersonId;
    }
    /**
     * @return integer
     */
    public function serviceId()
    {
        return $this->serviceId;
    }

    /**
     * @param $aServiceId
     */
    public function setServiceId($aServiceId)
    {
        $this->serviceId = $aServiceId;
    }
    /**
     * @return integer
     */
    public function hours()
    {
        return $this->hours;
    }

    /**
     * @param $aHours
     */
    public function setHourse($aHours)
    {
        $this->hours = $aHours;
    }
    /**
     * @return integer
     */
    public function cost()
    {
        return $this->cost;
    }

    /**
     * @param $aCost
     */
    public function setCost($aCost)
    {
        $this->cost = $aCost;
    }
}
