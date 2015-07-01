<?php

namespace webapp\modules\filemanager\validators;

use system\core\validators\AbstractValidator;

class StdUploadValidator extends AbstractValidator
{
    public function isValid($files)
    {
        foreach ($files as $file) {
            if ($file['error'] !== 0) {
                return false;
            }
        }

        return true;
    }
}