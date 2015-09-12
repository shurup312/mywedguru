<?php

namespace infrastructure\common\components;

class CommandBus implements \domain\common\contracts\CommandBus
{
    protected $commandMap = [];

    public function handle($aCommand)
    {
        $command = get_class($aCommand);
        if (!array_key_exists($command, $this->commandMap)) {
            throw new \LogicException(sprintf('The command \'%s\' is not supported', $command));
        }
        $handler = \Yii::createObject($this->commandMap[$command]);

        return $handler->handle($aCommand);
    }
}
