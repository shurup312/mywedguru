<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace frontend\ddd\person\repositories;

use app\ddd\person\factories\PersonFactory;
use app\modules\valueObjects\Sex;
use DateTime;
use frontend\ddd\interfaces\Repository;
use frontend\models\Person;
use frontend\models\UserType;
use yii\db\Query;

class PersonRepository extends Repository
{

    private $tableName = '{{%person}}';

    /**
     * @param Person $aPerson
     *
     * @return bool
     */
    public function save(Person &$aPerson)
    {
        if (!$aPerson->id()) {
            return $this->create($aPerson);
        } else {
            return $this->update($aPerson);
        }
    }

    public function getByID($aPersonId)
    {
        if (!$aPersonId) {
            return null;
        }
        $data = (new Query())->select('person.*, users.type')->from($this->tableName.' as person')->where(['person.id' => $aPersonId])
            ->innerJoin('{{%users}} as users', 'users.person_id = person.id')->one();
        $person = (new PersonFactory())->create();
        $person->setId($data['id']);
        $person->setFirstName($data['first_name']);
        $person->setLastName($data['last_name']);
        if ($data['sex']) {
            $person->setSex(new Sex($data['sex']));
        }
        if($data['date_birth']){
            $person->setDateBirth(new DateTime($data['date_birth']));
        }
        $person->setMobPhone($data['mob_phone']);
        $person->setPhone($data['phone']);
        $person->setAddress($data['address']);
        $person->setEmail($data['email']);
        $person->setAbout($data['about']);
        $person->setStudioId($data['studio_id']);
        $person->setType(new UserType($data['type']));
        return $person;
    }

    private function create(Person &$aPerson)
    {
        $db     = $this->getDb();
        $result = $db->createCommand()->insert($this->tableName, [
            'first_name' => $aPerson->firstName(),
            'last_name'  => $aPerson->lastName(),
            'studio_id'  => $aPerson->studioId(),
            'sex'        => $aPerson->sex() ? $aPerson->sex()->type() : null,
            'date_birth' => $aPerson->dateBirth() ? $aPerson->dateBirth()->format('Y-m-d H:i:s') : null,
            'mob_phone'  => $aPerson->mobPhone(),
            'phone'      => $aPerson->phone(),
            'address'    => $aPerson->address(),
            'email'      => $aPerson->email(),
            'about'      => $aPerson->about(),
        ])->execute();
        $aPerson->setId($db->getLastInsertID());
        return $result;
    }

    private function update(Person $aPerson)
    {
        $db     = $this->getDb();
        $result = $db->createCommand()->update($this->tableName, [
            'studio_id'  => $aPerson->studioId(),
            'first_name' => $aPerson->firstName(),
            'last_name'  => $aPerson->lastName(),
            'sex'        => $aPerson->sex() ? $aPerson->sex()->type() : null,
            'date_birth' => $aPerson->dateBirth() ? $aPerson->dateBirth()->format('Y-m-d H:i:s') : null,
            'mob_phone'  => $aPerson->mobPhone(),
            'phone'      => $aPerson->phone(),
            'address'    => $aPerson->address(),
            'email'      => $aPerson->email(),
            'about'      => $aPerson->about(),
        ], ['id' => $aPerson->id()])->execute();
        return $result;
    }
}
