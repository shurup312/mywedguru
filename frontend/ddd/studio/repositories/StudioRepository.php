<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace app\ddd\studio\repositories;

use app\ddd\studio\factories\StudioFactory;
use frontend\ddd\interfaces\Repository;
use frontend\models\Person;
use frontend\models\Studio;
use yii\db\Query;

class StudioRepository extends Repository
{

    private $studioTableName = '{{%studio}}';

    /**
     * @param Person $aPerson
     *
     * @return \frontend\models\Studio|null
     */
    public function getByPerson(Person $aPerson)
    {
        if (!$aPerson->studioId()) {
            return null;
        }
        $studio = (new StudioFactory())->create();
        $data   = (new Query())->select('studio.*')->from($this->studioTableName.' as studio')->where(['id' => $aPerson->studioId()])->one();
        $studio->setAddress($data['address']);
        $studio->setName($data['name']);
        $studio->setId($data['id']);
        $studio->setPhone($data['phone']);
        return $studio;
    }

    public function save(Studio &$studio)
    {
        if (!$studio->id()) {
            return $this->create($studio);
        } else {
            return $this->update($studio);
        }
    }

    private function create(Studio &$studio)
    {
        $db     = $this->getDb();
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
        $db     = $this->getDb();
        $result = $db->createCommand()->update($this->studioTableName, [
            'name'    => $studio->name(),
            'address' => $studio->address(),
            'phone'   => $studio->phone(),
        ], ['id' => $studio->id()])->execute();
        return $result;
    }
}
