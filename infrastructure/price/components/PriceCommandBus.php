<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:29
 */
namespace infrastructure\price\components;

use infrastructure\common\components\CommandBus;

class PriceCommandBus extends CommandBus implements \domain\price\contracts\PriceCommandBus
{

    protected $commandMap
        = [
            'infrastructure\price\commands\SavePersonServiceCommand' => 'infrastructure\price\commands\handlers\SavePersonServiceHandler',
        ];
}
