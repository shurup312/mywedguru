<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:41
 */
namespace infrastructure\wedding\components;

use infrastructure\common\components\CommandBus;

class WeddingCommandBus extends CommandBus implements \domain\wedding\contracts\WeddingCommandBus
{

    protected $commandMap = [
        'infrastructure\wedding\commands\CreateWeddingCommand' => 'infrastructure\wedding\commands\handlers\CreateWeddingHandler',
    ];
}
