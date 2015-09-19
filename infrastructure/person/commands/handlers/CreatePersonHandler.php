<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\person\commands\handlers;

use domain\person\components\PersonFactory;
use infrastructure\person\commands\CreatePersonCommand;
use infrastructure\person\components\PersonRepository;

class CreatePersonHandler
{
    private $personRepository;
    private $personFactory;

    public function __construct(PersonRepository $aPersonRepository, PersonFactory $aPersonFactory)
    {
        $this->personRepository = $aPersonRepository;
        $this->personFactory    = $aPersonFactory;
    }

    /**
     * @param CreatePersonCommand $aCommand
     *
     * @return \domain\person\entities\Person
     */
    public function handle(CreatePersonCommand $aCommand)
    {
        $person = $this->personFactory->createEmpty();
        $person->setFirstName($aCommand->firstName());
        $person->setLastName($aCommand->lastName());
        $this->personRepository->save($person);
        return $person;
    }
}
