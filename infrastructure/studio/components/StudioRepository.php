<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace infrastructure\studio\components;

use domain\person\entities\Person;
use domain\studio\entities\Studio;
use infrastructure\common\components\SqlRepository;
use yii\db\Query;

/**
 * Class StudioRepository
 *
 * @package infrastructure\studio\components
 * @property StudioFactory $studioFactory
 */
class StudioRepository extends SqlRepository
{

    private static $studioTableName = '{{%studio}}';

    private static function getFactory()
    {
        return new StudioFactory();
    }

    public static function getByPerson(Person $aPerson)
    {
        if (!$aPerson->studioId()) {
            return null;
        }
        $data   = (new Query())->select('studio.*')->from(self::$studioTableName.' as studio')->where(['id' => $aPerson->studioId()])->one();
        $studio = self::getFactory()->create($data['address'], $data['name'], $data['phone']);
        $studio->setId($data['id']);
        return $studio;
    }

    public function save(Studio $studio)
    {
        if (!$studio->id()) {
            return $this->create($studio);
        } else {
            return $this->update($studio);
        }
    }

    private function create(Studio &$studio)
    {
        $db     = $this->db();
        $result = $db->createCommand()->insert($this->studioTableName, [
            'name'    => $studio->name(),
            'address' => $studio->address(),
            'phone'   => $studio->phone(),
        ])->execute();
        $studio->setId($db->getLastInsertID());
        return $result;
    }

    private function update(Studio $studio)
    {
        $db     = $this->db();
        $result = $db->createCommand()->update($this->studioTableName, [
            'name'    => $studio->name(),
            'address' => $studio->address(),
            'phone'   => $studio->phone(),
        ], ['id' => $studio->id()])->execute();
        return $result;
    }
}
