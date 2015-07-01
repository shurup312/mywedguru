<?php

namespace webapp\modules\filemanager\entities;


class Directory extends \DirectoryIterator
{
    public function __construct($path, $mode = 0777)
    {
        $this->_path = trim($path);
        $this->_mode = $mode;
        $this->createPath();
        $this->_path = realpath($this->_path);
        parent::__construct($this->_path);

    }

    public function delete()
    {
        $fn = function ($dir) use (&$fn) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    $fn($path);
                } else {
                    unlink($path);
                }
            }

            return rmdir($dir);
        };

        return $fn($this->_path);
    }
    public function size()
    {
        $fn = function ($dir) use (&$fn) {
            $totalSize = 0;
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    $totalSize += $fn($path);
                } else {
                    $totalSize += filesize($path);
                }
            }

            return $totalSize;
        };

        return $fn($this->_path);
    }

    protected function createPath($path = null)
    {
        if ($path === null) {
            $path = $this->_path;
        }
        $mode = $this->_mode;
        if (is_dir($path)) {
            if ($mode && !chmod($path, $mode)) {
                throw new \Exception("Could not set mode to directory '$path'");
            }

            return;
        }
        $parent = dirname($path);
        if (!is_dir($parent)) {
            $this->createPath($parent);
        }
        try {
            if (!mkdir($path, $mode)) {
                throw new \Exception("Could not create directory '$path'");
            }
        } catch (\Exception $e) {
            throw new \Exception("Permission denied to create directory '$path'");
        }
    }

    private $_path;
    private $_mode;
}