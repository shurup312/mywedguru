<?php

namespace webapp\modules\filemanager\services;

class CreateLinkService extends ServiceAbstract
{
    const DEFAULT_EXTENSION = '.lnk';

    public $path;
    public $name;
    public $link;

    protected function loadResults()
    {
        $this->results = $this->fileManager()->createFile($this->name . self::DEFAULT_EXTENSION, $this->link, $this->decodePath($this->path));
    }
}