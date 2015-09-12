<?php

namespace domain\common\contracts;

interface CommandBus
{
    /**
     * @param $aCommand object
     * @return object|array
     */
    public function handle($aCommand);
}