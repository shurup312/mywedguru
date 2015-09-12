<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace domain\person\components;

use DateTime;
use domain\person\entities\Person;
use infrastructure\person\entities\User;

/**
 * @property User user
 */
class PersonFactory
{

    /**
     * @return Person
     */
    public function createEmpty()
    {
        $person = new Person();
        return $person;
    }

    /**
     * @param string        $aFirstName
     * @param string        $aLastName
     * @param string        $aMobilePhone
     * @param string        $aPhone
     * @param null|DateTime $aDateBirth
     * @param string        $anEmail
     * @param string        $anAddress
     * @param string        $anAbout
     *
     * @return Person
     */
    public function createByParams($aFirstName, $aLastName, $aMobilePhone, $aPhone, DateTime $aDateBirth, $anEmail, $anAddress, $anAbout)
    {
        $person = new Person();
        $person->setFirstName($aFirstName);
        $person->setLastName($aLastName);
        $person->setMobPhone($aMobilePhone);
        $person->setPhone($aPhone);
        if ($aDateBirth instanceof DateTime) {
            $person->setDateBirth($aDateBirth);
        }
        $person->setEmail($anEmail);
        $person->setAddress($anAddress);
        $person->setAbout($anAbout);
        return $person;
    }
}
