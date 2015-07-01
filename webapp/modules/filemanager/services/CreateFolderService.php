<?php

namespace webapp\modules\filemanager\services;

class CreateFolderService extends ServiceAbstract
{
    public $path;

    protected function loadResults()
    {
        $result = $this->fileManager()->createDirectory($this->decodePath($this->path));
        $this->results = $this->encodePath($result);
    }
}