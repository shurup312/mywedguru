<?php

namespace webapp\modules\filemanager\validators;


class LimitMemoryValidator
{
    public function __construct($availableMemory, $startValue = 0)
    {
        $this->availableMemory = $availableMemory;
        $this->startValue = $startValue;
    }

    public function isValid($files)
    {
        if ($this->availableMemory !== null) {
            $startValue = $this->startValue;
            foreach ($files as $file) {
                $startValue += $file['size'];
                if ($startValue > $this->availableMemory) {
                    return false;
                }
            }
        }

        return true;
    }

    protected $availableMemory;
    protected $startValue;
}