<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 19:39
 */
namespace infrastructure\person\commands\handlers;

use domain\person\components\PersonFactory;
use infrastructure\person\commands\UpdatePersonRawCommand;
use infrastructure\person\components\PersonRepository;

class UpdatePersonRawHandler
{

    private $personRepository;

    public function __construct(PersonRepository $aPersonRepository)
    {
        $this->personRepository = $aPersonRepository;
    }

    public function handle(UpdatePersonRawCommand $aCommand)
    {
        $person = $aCommand->person();
        $person->setFirstName($aCommand->firstName());
        $person->setLastName($aCommand->lastName());
        $person->setMobPhone($aCommand->mobilePhone());
        $person->setPhone($aCommand->phone());
        $person->setDateBirth($aCommand->dateBirth());
        $person->setEmail($aCommand->email());
        $person->setAddress($aCommand->address());
        $person->setAbout($aCommand->about());
        return $this->personRepository->save($person);
    }
}
