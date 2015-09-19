<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 17:29
 */
namespace infrastructure\person\components;

use infrastructure\common\components\CommandBus;

class PersonCommandBus extends CommandBus implements \domain\person\contracts\PersonCommandBus
{

    protected $commandMap
        = [
            'infrastructure\person\commands\UpdatePersonCommand'     => 'infrastructure\person\commands\handlers\UpdatePersonHandler',
            'infrastructure\person\commands\UpdatePersonRawCommand'  => 'infrastructure\person\commands\handlers\UpdatePersonRawHandler',
            'infrastructure\person\commands\CreatePersonCommand'     => 'infrastructure\person\commands\handlers\CreatePersonHandler',
            'infrastructure\person\commands\GetCurrentPersonCommand' => 'infrastructure\person\commands\handlers\GetCurrentPersonHandler',
        ];
}
