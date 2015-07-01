<?php

namespace webapp\modules\filemanager\entities;


class FileManager
{
    public function __construct($rootPath)
    {
        $this->root = new Directory($rootPath);
    }

    public function createFile($name, $content, $path = null)
    {
        if ($path) {
            $path = $this->getRealPath($path);
            $fileName = $this->createFileName($path . DIRECTORY_SEPARATOR . $name);
        } else {
            $fileName = $this->createFileName($this->getRealPath($name));
        }
        if (file_put_contents($fileName, $content) === false) {
            throw new \Exception("File system error");
        }

        return basename($fileName);
    }

    public function moveUploadedFiles(array $files, $path = null)
    {
        foreach ($files as $file) {
            if ($path) {
                $path = $this->getRealPath($path);
                $fileName = $this->createFileName($path . DIRECTORY_SEPARATOR . $file['name']);
            } else {
                $fileName = $this->createFileName($this->getRealPath($file['name']));
            }
            move_uploaded_file($file['tmp_name'], $fileName);
        }
    }

    public function getRealPath($path)
    {
        return $this->root->getRealPath() . DIRECTORY_SEPARATOR . str_replace('..', '', trim($path, '\\/'));
    }

    public function createFileName($fileName)
    {
        $additional = 1;
        $info = pathinfo($fileName);
        while (file_exists($fileName)) {
            $fileName = $info['dirname'] . '/' . $info['filename'] . '(' . $additional . ').' . $info['extension'];
            $additional++;
        }

        return $fileName;
    }

    public function getDirectoryList($path = null)
    {
        $path = $this->root->getRealPath() . DIRECTORY_SEPARATOR . trim($path, '\\/');
        if (!is_dir($path)) {
            throw new \Exception("Directory '$path' is not exists");
        }
        $directory = new Directory($path);
        $items = [];
        foreach ($directory as $file) {
            if ($file->isDir() && !$file->isDot()) {
                $items[] = clone($file);
            }
        }

        return $items;
    }

    public function getFileList($path = null)
    {
        $path = $this->root->getRealPath() . DIRECTORY_SEPARATOR . trim($path, '\\/');
        if (!is_dir($path)) {
            throw new \Exception("Directory '$path' is not exists");
        }
        $directory = new Directory($path);
        $items = [];
        foreach ($directory as $file) {
            if ($file->isFile()) {
                $items[] = clone($file);
            }
        }

        return $items;
    }

    public function createDirectory($path)
    {
        $path = $this->root->getRealPath() . DIRECTORY_SEPARATOR . str_replace('.', '', trim($path, '\\/ '));

        return new Directory($path);
    }

    public function delete($path)
    {
        $path = $this->root->getRealPath() . DIRECTORY_SEPARATOR . trim($path, '\\/');
        if (is_dir($path)) {
            $directory = new Directory($path);
            $directory->delete();
        } else {
            unlink($path);
        }
    }

    public function getTotalSize()
    {
        return $this->root->size();
    }

    protected $root;
}