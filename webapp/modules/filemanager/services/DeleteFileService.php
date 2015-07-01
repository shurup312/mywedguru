<?php

namespace webapp\modules\filemanager\services;

class DeleteFileService extends ServiceAbstract
{
    public $id;

    protected function loadResults()
    {
        if (is_array($this->id)) {
            foreach ($this->id as $path) {
                $this->fileManager()->delete($this->decodePath($path));
            }
        } else {
            $this->fileManager()->delete($this->decodePath($this->id));
        }

        $this->results = ['id' => $this->id];
    }
}