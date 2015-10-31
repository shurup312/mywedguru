<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 30.08.2015
 * Time: 13:02
 */
namespace domain\service\components;

use domain\service\entities\Service;

class ServiceFactory
{

    /**
     * @return Service
     */
    public function createEmpty()
    {
        $service = new Service();
        return $service;
    }
}
