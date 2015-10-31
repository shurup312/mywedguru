<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:29
 */
namespace infrastructure\service\components;

use infrastructure\common\components\CommandBus;

class ServiceCommandBus extends CommandBus implements \domain\service\contracts\ServiceCommandBus
{

    protected $commandMap
        = [
            'infrastructure\service\commands\GetServicesByUserTypeCommand' => 'infrastructure\service\commands\handlers\GetServicesByUserTypeHandler',
        ];
}
