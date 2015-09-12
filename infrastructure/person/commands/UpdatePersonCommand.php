<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\person\commands;

use domain\person\entities\Person;

class UpdatePersonCommand
{
    private $person;
    public function __construct(Person $aPerson)
    {
        $this->person = $aPerson;
    }

    public function person()
    {
        return $this->person;
    }
}
