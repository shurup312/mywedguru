<?php

namespace webapp\modules\filemanager\assets;

use system\core\HTML\Asset;

class FileManagerAsset extends Asset
{
    public function package()
    {
        return [
            'js' => [
                '/public/components/filemanager/js/filemanager.js',
            ],
            'css' => [
                '/public/components/filemanager/css/filemanager.css',
            ],
        ];
    }

    public function dependency()
    {
        return [];
    }
}
