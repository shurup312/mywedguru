<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\service\commands;

use domain\person\values\UserType;

class GetServicesByUserTypeCommand
{
    private $userType;
    /**
     * @param UserType $anUserType
     */
    public function __construct(UserType $anUserType)
    {
        $this->userType = $anUserType;
    }

    /**
     * @return UserType
     */
    public function userType()
    {
        return $this->userType;
    }
}
