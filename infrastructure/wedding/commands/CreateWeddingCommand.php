<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:46
 */
namespace infrastructure\wedding\commands;


use DateTime;
use domain\person\entities\Person;

class CreateWeddingCommand
{
    private $groom;
    private $bride;
    private $date;

    public function __construct(Person $aGroom, Person $aBride, $aDate)
    {
        $this->groom = $aGroom;
        $this->bride = $aBride;
        $this->date = new DateTime($aDate);
    }

    /**
     * @return Person
     */
    public function groom()
    {
        return $this->groom;
    }

    /**
     * @return Person
     */
    public function bride()
    {
        return $this->bride;
    }
    /**
     * @return DateTime
     */
    public function date()
    {
        return $this->date;
    }


}
