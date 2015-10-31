<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 29.08.2015
 * Time: 17:32
 */
namespace infrastructure\person\components;

use DateTime;
use domain\common\exceptions\SqlRepositoryException;
use domain\person\components\PersonFactory;
use domain\person\entities\Person;
use domain\person\values\Sex;
use infrastructure\common\components\SqlRepository;
use infrastructure\person\entities\User;
use yii\db\Query;

class PersonRepository extends SqlRepository
{

    private static $tableName = '{{%person}}';

    /**
     * @param $aSlug
     *
     * @return Person|null
     */
    public static function getBySlug($aSlug)
    {
        if (!$aSlug) {
            return null;
        }
        $data = (new Query())->select('person.*')->from(self::$tableName.' as person')->where(['users.slug' => $aSlug])
            ->innerJoin('{{%users}} as users', 'users.person_id = person.id')->one();
        if(!$data){
            return null;
        }
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
        return $person;
    }

    /**
     * @param Person $aPerson
     *
     * @return bool
     */
    public function save(Person $aPerson)
    {
        if (!$aPerson->id()) {
            $this->create($aPerson);
        } else {
            $this->update($aPerson);
        }
    }

    /**
     * @param $aPersonId
     *
     * @return Person|null
     */
    public static function getByID($aPersonId)
    {
        if (!$aPersonId) {
            return null;
        }
        $data = (new Query())->select('person.*')->from(self::$tableName.' as person')->where(['person.id' => $aPersonId])->one();
        if(!$data){
            return null;
        }
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
        return $person;
    }

    private function create(Person $aPerson)
    {
        $db     = $this->db();
        $db->createCommand()->insert(self::$tableName, [
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
    }

    private function update(Person $aPerson)
    {
        $db     = $this->db();
        $db->createCommand()->update(self::$tableName, [
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
    }

    public static function getByUser(User $aUser)
    {
        if(!$aUser->id()){
            throw new SqlRepositoryException('Не передан обязательный параметр для получения персоны.');
        }
        return (new self())->getByID($aUser->personId());
    }

    public function getUserByPerson(Person $aPerson)
    {
        return User::find()->where(['person_id'=>$aPerson->id()])->one();
    }
}
