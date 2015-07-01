<?php

namespace webapp\modules\filemanager\validators;

use system\core\validators\AbstractValidator;

class AllowTypeValidator extends AbstractValidator
{
    public function __construct($allowMimeTypes)
    {
        $this->allowMimeTypes = $allowMimeTypes;
    }

    public function isValid($files)
    {
        if (is_array($this->allowMimeTypes)) {
            foreach ($files as $file) {
                if (!in_array($file['type'], $this->allowMimeTypes)) {
                    return false;
                }
            }
        }

        return true;
    }

    protected $allowMimeTypes;
}