<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\person\commands;

use domain\person\values\UserType;

class CreatePersonCommand
{

    private $firstName;
    private $lastName;

    public function __construct($aFirstName, $aLastName)
    {
        $this->firstName = $aFirstName;
        $this->lastName  = $aLastName;
    }

    /**
     * @return string
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * @return UserType
     */
    public function type()
    {
        return $this->type;
    }
}
