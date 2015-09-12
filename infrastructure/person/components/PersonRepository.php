<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace infrastructure\person\components;

use DateTime;
use domain\person\components\PersonFactory;
use domain\person\entities\Person;
use domain\person\values\Sex;
use domain\person\values\UserType;
use infrastructure\common\components\SqlRepository;
use infrastructure\person\entities\User;
use yii\db\Exception;
use yii\db\Query;

class PersonRepository extends SqlRepository
{

    private $tableName = '{{%person}}';

    /**
     * @param Person $aPerson
     *
     * @return bool
     */
    public function save(Person $aPerson)
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
        $person = (new PersonFactory())->createEmpty();
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

    private function create(Person $aPerson)
    {
        $db     = $this->db();
        try {
            $db->createCommand()->insert($this->tableName, [
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
        } catch (Exception $e){
            return false;
        }
        $aPerson->setId($db->getLastInsertID());
        return true;
    }

    private function update(Person $aPerson)
    {
        $db     = $this->db();
        try {
            $db->createCommand()->update($this->tableName, [
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
        } catch (Exception $e){
        	return false;
        }
        return true;
    }

    public static function getByUser(User $aUser)
    {
        if(!$aUser || !$aUser->id){
            return null;
        }
        return (new self())->getByID($aUser->personId());

    }
}
