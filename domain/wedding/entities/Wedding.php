<?php

namespace domain\wedding\entities;

use DateTime;

/**
 * This is the model class for table "wedding".
 *
 */
class Wedding
{

    private $id;
    private $groomId;
    private $brideId;
    private $date;

    public function __construct($aGroomId, $aBrideId, DateTime $aDate)
    {
        $this->groomId = $aGroomId;
        $this->brideId = $aBrideId;
        $this->date = $aDate;
    }

    /**
     * @param $anId
     */
    public function setId($anId)
    {
        $this->id = $anId;
    }
    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function groomId()
    {
        return $this->groomId;
    }

    /**
     * @return mixed
     */
    public function brideId()
    {
        return $this->brideId;
    }

    /**
     * @return DateTime
     */
    public function date()
    {
        return $this->date;
    }

}
