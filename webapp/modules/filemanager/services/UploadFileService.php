<?php

namespace webapp\modules\filemanager\services;


use webapp\modules\filemanager\validators\AllowTypeValidator;
use webapp\modules\filemanager\validators\DenyTypeValidator;
use webapp\modules\filemanager\validators\LimitMemoryValidator;
use webapp\modules\filemanager\validators\StdUploadValidator;

class UploadFileService extends ServiceAbstract
{
    const ERROR_UPLOAD = 'Ошибка при загрузке файла на сервер';
    const ERROR_DENY_TYPE = 'Один из файлов имеет недопустимый тип';
    const ERROR_MEMORY_LIMIT = 'Превышен лимит доступной памяти';

    public $files = [];

    protected function loadResults()
    {
        $validator = new StdUploadValidator();
        if (!$validator->isValid($this->files)) {
            throw new \Exception(self::ERROR_UPLOAD);
        }
        $validator = new LimitMemoryValidator($this->settings(static::CONFIG_AVAILABLE_MEMORY), $this->fileManager()->getTotalSize());
        if (!$validator->isValid($this->files)) {
            throw new \Exception(self::ERROR_MEMORY_LIMIT);
        }
        $validator = new AllowTypeValidator($this->settings(static::CONFIG_ALLOW_MIME_TYPE));
        if (!$validator->isValid($this->files)) {
            throw new \Exception(self::ERROR_DENY_TYPE);
        }
        $validator = new DenyTypeValidator($this->settings(static::CONFIG_DENY_MIME_TYPE));
        if (!$validator->isValid($this->files)) {
            throw new \Exception(self::ERROR_DENY_TYPE);
        }
        foreach ($this->files as &$file) {
            $file['name'] = $this->decodePath($file['name']);
            $file['name'] = str_replace('&', '', $file['name']);
        }
        $this->fileManager()->moveUploadedFiles($this->files, $this->decodePath());
    }
}