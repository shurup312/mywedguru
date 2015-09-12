<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\person\commands\handlers;

use infrastructure\person\commands\UpdatePersonCommand;
use infrastructure\person\components\PersonRepository;

class UpdatePersonHandler
{
    private $personRepository;

    public function __construct(PersonRepository $aPersonRepository)
    {
        $this->personRepository = $aPersonRepository;
    }

    public function handle(UpdatePersonCommand $aCommand)
    {
        return $this->personRepository->save($aCommand->person());
    }
}
