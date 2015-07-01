<?php

namespace webapp\modules\filemanager\services;

class ReadFileService extends ServiceAbstract
{
    protected $file = null;

    protected function loadResults()
    {
        $file = new \StdClass();
        $file->path = $this->decodePath($this->fileManager()->getRealPath($this->file));
        if (!is_file($file->path)) {
            throw new \Exception('File not found');
        }
        $file->type = (new \finfo())->file($file->path, FILEINFO_MIME_TYPE);
        $file->content = file_get_contents($file->path);
        $file->size = filesize($file->path);
        $file->name = basename($file->path);

        $this->results = $file;
    }
}