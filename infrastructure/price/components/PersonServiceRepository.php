<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace infrastructure\price\components;

use domain\person\entities\Person;
use domain\price\components\PersonServiceFactory;
use domain\price\entities\PersonService;
use domain\service\entities\Service;
use infrastructure\common\components\SqlRepository;
use yii\db\Query;

class PersonServiceRepository extends SqlRepository
{

    private static $tableName = '{{%person_service}}';

    /**
     * @param Person  $aPerson
     * @param Service $aService
     *
     * @return PersonService|null
     */
    public static function getByPersonAndServiceId(Person $aPerson, Service $aService)
    {
        $data = (new Query())->select('user_service.*')->from(self::$tableName.' as user_service')->where([
            'user_service.person_id'  => $aPerson->id(),
            'user_service.service_id' => $aService->id(),
        ])->one();
        if (!$data) {
            return null;
        }
        /**
         * TODO: сделать чтобы присваивания делала факторка
         */
        $service = (new PersonServiceFactory())->createEmpty();
        $service->setId($data['id']);
        $service->setPersonId($data['person_id']);
        $service->setServiceId($data['service_id']);
        $service->setHourse($data['hours']);
        $service->setCost($data['cost']);
        return $service;
    }

    /**
     * @param PersonService $anUserService
     *
     * @return bool
     */
    public function save(PersonService $anUserService)
    {
        if (!$anUserService->id()) {
            $this->create($anUserService);
        } else {
            $this->update($anUserService);
        }
    }

    /**
     * @param PersonService $anPersonService
     *
     * @throws \yii\db\Exception
     */
    private function create(PersonService $anPersonService)
    {
        $db = $this->db();
        $db->createCommand()->insert(self::$tableName, [
            'person_id'  => $anPersonService->personId(),
            'service_id' => $anPersonService->serviceId(),
            'hours'      => $anPersonService->hours(),
            'cost'       => $anPersonService->cost(),
        ])->execute();
        $anPersonService->setId($db->getLastInsertID());
    }

    /**
     * @param PersonService $anPersonService
     *
     * @throws \yii\db\Exception
     */
    private function update(PersonService $anPersonService)
    {
        $db = $this->db();
        $db->createCommand()->update(self::$tableName, [
            'person_id'  => $anPersonService->personId(),
            'service_id' => $anPersonService->serviceId(),
            'hours'      => $anPersonService->hours(),
            'cost'       => $anPersonService->cost(),
        ], ['id' => $anPersonService->id()])->execute();
    }
}
