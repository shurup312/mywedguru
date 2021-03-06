<?php
namespace domain\person\entities;

use DateTime;
use domain\common\components\Entity;
use domain\person\values\Sex;
use infrastructure\person\components\PersonRepository;
use infrastructure\person\entities\User;

/**
 * Class Person
 *
 * @package domain\person\entities
 *
 * @property User $user
 */
class Person extends Entity
{
    protected $id;
    protected $firstName;
    protected $lastName;
    protected $sex;
    protected $dateBirth;
    protected $mobPhone;
    protected $phone;
    protected $address;
    protected $email;
    protected $about;
    protected $studioId;
    private $user;

    /**
     * @return string
     */
    public function studioId()
    {
        return $this->studioId;
    }
    /**
     * @param string $studio_id
     */
    public function setStudioId($studio_id)
    {
        $this->studioId = $studio_id;
    }
    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param $anId
     *
     */
    public function setId($anId)
    {
        $this->id = $anId;
    }

    /**
     * @return string
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Sex
     */
    public function sex()
    {
        return $this->sex;
    }

    /**
     * @param Sex $sex
     */
    public function setSex(Sex $sex)
    {
        $this->sex = $sex->type();
    }

    /**
     * @return DateTime|null
     */
    public function dateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * @param DateTime $dateBirth
     */
    public function setDateBirth(DateTime $dateBirth)
    {
        $this->dateBirth = $dateBirth;
    }

    /**
     * @return string
     */
    public function mobPhone()
    {
        return $this->mobPhone;
    }

    /**
     * @param string $mobPhone
     */
    public function setMobPhone($mobPhone)
    {
        $this->mobPhone = $mobPhone;
    }

    /**
     * @return string
     */
    public function phone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function about()
    {
        return $this->about;
    }

    /**
     * @param string $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @return User
     */
    public function user()
    {
        if(!$this->user){
            $this->user = (new PersonRepository())->getUserByPerson($this);
        }

        return $this->user;
    }

    public function equalsTo(Person $aPerson)
    {
        return $this->id() === $aPerson->id();
    }
}
