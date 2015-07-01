<?php

namespace webapp\modules\filemanager\services;


class GetListService extends ServiceAbstract
{
    protected $filter;

    protected function loadResults()
    {
        if ($this->filter === 'files') {
            $result = $this->fileManager()->getFileList($this->decodePath());
        } else {
            $result = $this->fileManager()->getDirectoryList($this->decodePath());
        }

        $this->results = $this->encodePath($result);
    }
}