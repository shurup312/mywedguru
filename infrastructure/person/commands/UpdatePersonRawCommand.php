<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 19:28
 */
namespace infrastructure\person\commands;

use domain\person\entities\Person;

class UpdatePersonRawCommand
{
    private $person;
    private $firstName;
    private $lastName;
    private $mobilePhone;
    private $phone;
    private $dateBirth;
    private $email;
    private $address;
    private $about;

    public function __construct(Person $aPerson, $aFirstName, $aLastName, $aMobilePhone, $aPhone, $aDateBirth, $anEmail, $anAddress, $anAbout)
    {
        $this->person      = $aPerson;
        $this->firstName   = $aFirstName;
        $this->lastName    = $aLastName;
        $this->mobilePhone = $aMobilePhone;
        $this->phone       = $aPhone;
        $this->dateBirth   = new \DateTime($aDateBirth);
        $this->email       = $anEmail;
        $this->address     = $anAddress;
        $this->about       = $anAbout;
    }

    /**
     * @return Person
     */
    public function person()
    {
        return $this->person;
    }

    /**
     * @return mixed
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function mobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * @return mixed
     */
    public function phone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function dateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function address()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function about()
    {
        return $this->about;
    }
}
