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

class GetCurrentPersonHandler
{
    private $personRepository;

    public function __construct(PersonRepository $aPersonRepository, PersonFactory $aPersonFactory)
    {
        $this->personRepository = $aPersonRepository;
    }

    /**
     * @return \domain\person\entities\Person
     */
    public function handle()
    {
        return $this->personRepository->getByUser(\Yii::$app->getUser()->identity);
    }
}
