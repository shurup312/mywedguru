<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\price\commands\handlers;

use domain\price\components\PersonServiceFactory;
use infrastructure\price\commands\SavePersonServiceCommand;
use infrastructure\price\components\PersonServiceRepository;

class SavePersonServiceHandler
{

    private $personRepository;

    public function __construct(PersonServiceRepository $aPersonRepository)
    {
        $this->personRepository = $aPersonRepository;
    }

    /**
     * @param GetServicesByUserTypeCommand $aCommand
     *
     * @return \domain\person\entities\Person
     */
    public function handle(SavePersonServiceCommand $aCommand)
    {
        /**
         * TODO: сделать, чтобы присваивания делала факторка
         */
        $personService = PersonServiceRepository::getByPersonAndServiceId($aCommand->person(), $aCommand->service());
        if (!$personService) {
            $personService = (new PersonServiceFactory())->createEmpty();
            $personService->setPersonId($aCommand->person()->id());
            $personService->setServiceId($aCommand->service()->id());
        }
        $personService->setHourse($aCommand->hours());
        $personService->setCost($aCommand->cost());
        $this->personRepository->save($personService);
    }
}
