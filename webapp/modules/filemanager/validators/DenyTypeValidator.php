<?php

namespace webapp\modules\filemanager\validators;


class DenyTypeValidator
{
    public function __construct($denyMimeTypes)
    {
        $this->denyMimeTypes = $denyMimeTypes;
    }

    public function isValid($files)
    {
        if (is_array($this->denyMimeTypes)) {
            foreach ($files as $file) {
                if (in_array($file['type'], $this->denyMimeTypes)) {
                    return false;
                }
            }
        }

        return true;
    }

    protected $denyMimeTypes;
}