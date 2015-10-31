<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 31.10.2015
 * Time: 17:53
 */
namespace cabinet\controllers\photographer;

use domain\person\entities\Person;
use domain\person\values\UserType;
use domain\service\entities\Service;
use infrastructure\common\components\CommandBusList;
use infrastructure\person\commands\GetCurrentPersonCommand;
use infrastructure\price\commands\SavePersonServiceCommand;
use infrastructure\price\components\PersonServiceRepository;
use infrastructure\service\commands\GetServicesByUserTypeCommand;
use yii\base\Action;

class SavePriceAction extends Action
{

    public function run()
    {
        /**
         * @var Person $person
         */
        $person            = CommandBusList::getPersonCommanBus()->handle(new GetCurrentPersonCommand());
        $serviceList       = CommandBusList::getServiceCommanBus()->handle(new GetServicesByUserTypeCommand($person->user()->type()));
        $isSaved           = $this->saveIfIsRequest($person);
        $personServiceList = $this->prepareSeviceList($person, $serviceList);
        $hoursArray        = $this->prepareDropDownBox();
        return $this->controller->renderPartial((new UserType(UserType::USER_PHOTOGRAPGER))->prefix().'/_services', [
            'serviceList'       => $serviceList,
            'personServiceList' => $personServiceList,
            'hoursArray'        => $hoursArray,
            'isSaved'           => $isSaved,
        ]);
    }

    /**
     * @param Person $person
     *
     * @return bool
     */
    private function saveIfIsRequest(Person $person)
    {
        if (($hours = \Yii::$app->request->post('hours')) && ($cost = \Yii::$app->request->post('cost'))) {
            foreach ($cost as $serviceId => $item) {
                if ($hours[$serviceId] && $cost[$serviceId]) {
                    CommandBusList::getPriceCommandBud()->handle(new SavePersonServiceCommand($person, $serviceId, $hours[$serviceId],
                        $cost[$serviceId]));
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @param Person    $person
     * @param Service[] $serviceList
     *
     * @return mixed
     */
    private function prepareSeviceList($person, $serviceList)
    {
        $result = [];
        foreach ($serviceList as $service) {
            $personService = PersonServiceRepository::getByPersonAndServiceId($person, $service);
            if ($personService) {
                $result[$personService->serviceId()] = $personService;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    private function prepareDropDownBox()
    {
        $hoursArray = [];
        for ($i = 1; $i < 17; $i++) {
            $hoursArray[$i] = $i;
        }
        return $hoursArray;
    }
}
