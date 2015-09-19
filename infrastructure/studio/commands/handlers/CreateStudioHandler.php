<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:46
 */
namespace infrastructure\studio\commands\handlers;

use domain\common\exceptions\exceptions\ServiceException;
use domain\studio\components\StudioFactory;
use infrastructure\person\components\PersonRepository;
use infrastructure\studio\commands\CreateStudioCommand;
use infrastructure\studio\components\StudioRepository;

class CreateStudioHandler
{

    private $personRepository;
    private $studioFactory;

    public function __construct(StudioFactory $aStudioFactory, PersonRepository $aPersonRepository, StudioRepository $aStudioRepository)
    {
        $this->personRepository = $aPersonRepository;
        $this->studioFactory    = $aStudioFactory;
        $this->studioRepository = $aStudioRepository;
    }

    /**
     * @param CreateStudioCommand $aCommand
     *
     * @return \domain\studio\entities\Studio
     * @throws ServiceException
     */
    public function handle(CreateStudioCommand $aCommand)
    {
        if (!$this->studioRepository->save($aCommand->studio())) {
            throw new ServiceException('Не удалось сохранить студию');
        }
        return $aCommand->studio();
    }
}
