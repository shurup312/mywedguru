<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:30
 */
namespace infrastructure\service\commands\handlers;

use infrastructure\service\commands\GetServicesByUserTypeCommand;
use infrastructure\service\components\ServiceRepository;

class GetServicesByUserTypeHandler
{
    private $serviceReposiroty;

    public function __construct(ServiceRepository $aServiceRepository)
    {
        $this->serviceReposiroty = $aServiceRepository;
    }

    /**
     * @param GetServicesByUserTypeCommand $aCommand

     *
*@return \domain\person\entities\Person
     */
    public function handle(GetServicesByUserTypeCommand $aCommand)
    {
        return $this->serviceReposiroty->getByUserType($aCommand->userType());
    }
}
