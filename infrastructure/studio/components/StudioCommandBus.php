<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 12.09.2015
 * Time: 16:41
 */
namespace infrastructure\studio\components;

use infrastructure\common\components\CommandBus;

class StudioCommandBus extends CommandBus implements \domain\studio\contracts\StudioCommandBus
{

    protected $commandMap = [
        'infrastructure\studio\commands\CreateStudioCommand' => 'infrastructure\studio\commands\handlers\CreateStudioHandler',
    ];
}
