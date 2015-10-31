<?php
namespace domain\person\entities;

use domain\common\components\Entity;
use domain\price\entities\Service;

/**
 * Class Price
 *
 * @package domain\price\entities
 * @property Service[] $services
 */
class Price extends Entity
{

    protected $services = [];

    /**
     * @return string
     */
    public function services()
    {
        return $this->services;
    }

    /**
     * @param Service $aService
     */
    public function addService(Service $aService)
    {
        $this->services[] = $aService;
    }
}
