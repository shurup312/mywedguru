<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace domain\price\components;

use domain\price\entities\PersonService;

class PersonServiceFactory
{

    /**
     * @return PersonService
     */
    public function createEmpty()
    {
        $userService = new PersonService();
        return $userService;
    }
}
