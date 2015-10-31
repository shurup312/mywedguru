<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace infrastructure\service\components;

use domain\service\components\ServiceFactory;
use domain\person\values\UserType;
use domain\service\entities\Service;
use infrastructure\common\components\SqlRepository;
use yii\db\Query;

class ServiceRepository extends SqlRepository
{

    private static $tableName = '{{%service}}';

    /**
     * @param UserType $anUserType
     *
     * @return null|Service[]
     */
    public static function getByUserType(UserType $anUserType)
    {
        if (!$anUserType) {
            return null;
        }
        $data = (new Query())->select('service.*')->from(self::$tableName.' as service')->where(['service.user_type' => $anUserType->type()])->all();
        if (!$data) {
            return null;
        }
        $result = [];
        foreach ($data as $oneItem) {
            $service = (new ServiceFactory())->createEmpty();
            $service->setId($oneItem['id']);
            $service->setName($oneItem['name']);
            $service->setUserType(new UserType($oneItem['user_type']));
            $result[] = $service;
        }
        return $result;
    }

    /**
     * @param $aServiceId
     *
     * @return Service|null
     */
    public static function getByID($aServiceId)
    {
        if (!$aServiceId) {
            return null;
        }
        $data = (new Query())->select('service.*')->from(self::$tableName.' as service')->where(['service.id' => $aServiceId])->one();
        if (!$data) {
            return null;
        }
        $service = (new ServiceFactory())->createEmpty();
        $service->setId($data['id']);
        $service->setName($data['name']);
        $service->setUserType(new UserType($data['user_type']));
        return $service;
    }
}
