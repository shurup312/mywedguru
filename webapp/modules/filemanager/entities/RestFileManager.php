<?php

namespace webapp\modules\filemanager\entities;


class RestFileManager extends FileManager
{
    public function getDirectoryList($path = null)
    {
        $list = parent::getDirectoryList($path);
        $start = strlen($this->root->getRealPath()) + 1;
        $items = [];
        foreach ($list as $file) {
            $items[] = ['id' => substr($file->getRealPath(), $start)];
        }

        return $items;
    }

    public function getFileList($path = null)
    {
        $list = parent::getFileList($path);
        $start = strlen($this->root->getRealPath()) + 1;
        $items = [];
        foreach ($list as $file) {
            $items[] = [
                'id' => substr($file->getRealPath(), $start),
                'size' => $file->getSize(),
                'modified' => date('d.m.Y H:i:s', $file->getMTime()),
                'content' => $file->getExtension() == 'lnk' ? file_get_contents($file->getRealPath()) : '',
                'type' => $file->getExtension(),
            ];
        }

        return $items;
    }

    public function createDirectory($path)
    {
        $directory = parent::createDirectory($path);
        $start = strlen($this->root->getRealPath()) + 1;

        return [
            'id' => substr($directory->getRealPath(), $start)
        ];
    }
}