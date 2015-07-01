<?php

namespace webapp\modules\filemanager\services;

class GetMemoryInfoService extends ServiceAbstract
{
    protected function loadResults()
    {
        $this->results = [
            'availableMemory' => $this->settings(static::CONFIG_AVAILABLE_MEMORY),
            'occupiedMemory' => $this->fileManager()->getTotalSize(),
        ];
    }
}