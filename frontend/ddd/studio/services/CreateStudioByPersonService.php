<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 06.09.2015
 * Time: 16:14
 */
namespace app\ddd\studio\services;

use app\ddd\exceptions\exceptions\ServiceException;
use app\ddd\studio\factories\StudioFactory;
use app\ddd\studio\repositories\StudioRepository;
use frontend\ddd\person\repositories\PersonRepository;
use frontend\models\Person;
use frontend\models\Studio;

class CreateStudioByPersonService
{

    private $studio;
    private $person;
    private $studioRepository;
    private $personRepository;

    public function __construct(StudioRepository $aStudioRepository, PersonRepository $aPersonRepository, Studio $aStudio, Person $aPerson)
    {
        $this->studio = $aStudio;
        $this->person = $aPerson;
        $this->studioRepository = $aStudioRepository;
        $this->personRepository = $aPersonRepository;
    }
    public function execute($name, $phone, $address)
    {
        $studio = (new StudioFactory())->create();
        $studio->setName($name);
        $studio->setPhone($phone);
        $studio->setAddress($address);
        if (!$this->studioRepository->save($this->studio)) {
            throw new ServiceException('Не удалось сохранить студию');
        }
        $this->person->setStudioId($this->studio->id());
        if(!$this->personRepository->save($this->person)){
            throw new ServiceException('Не удалось прикрепить студию');
        }
    }
}
