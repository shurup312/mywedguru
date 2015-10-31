<?php
namespace domain\service\entities;

use domain\common\components\Entity;
use domain\person\values\UserType;

/**
 * Class Person
 *
 * @package domain\person\entities
 * @property UserType $userType
 * @property string   $name
 */
class Service extends Entity
{

    protected $id;
    protected $userType;
    protected $name;

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param $anId
     */
    public function setId($anId)
    {
        $this->id = $anId;
    }

    /**
     * @return UserType
     */
    public function userType()
    {
        return $this->userType;
    }

    /**
     * @param UserType $anUserType
     */
    public function setUserType(UserType $anUserType)
    {
        $this->userType = $anUserType;
    }

    /**
     * @return integer
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param $aName
     */
    public function setName($aName)
    {
        $this->name = $aName;
    }
}
