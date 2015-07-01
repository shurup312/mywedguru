<?php

namespace webapp\modules\filemanager\services;

use system\core\App;
use system\core\base\Service;
use system\core\helpers\ArrayHelper;
use webapp\modules\filemanager\entities\RestFileManager;

/**
 * Class ServiceAbstract
 * @package modules\filemanager\services
 */
abstract class ServiceAbstract extends Service
{
    const CONFIG_ROOT = 'root';
    const CONFIG_CLOUD_URL = 'cloudUrl';
    const CONFIG_ALLOW_MIME_TYPE = 'allowMimeTypes';
    const CONFIG_DENY_MIME_TYPE = 'denyMimeTypes';
    const CONFIG_AVAILABLE_MEMORY = 'availableMemory';
    const CONFIG_BASE_CLOUD_PATH = 'baseCloudPath';
    const DEFAULT_CLIENT_SEPARATOR = '|';

    public $clientSeparator = self::DEFAULT_CLIENT_SEPARATOR;

    /**
     * @var null
     * @value courses|theme|...any entity
     */
    public $targetType = null;

    /**
     * @var null
     * @type integer
     */
    public $targetId = null;

    protected function decodePath($path = null)
    {
        if ($path === null && isset($_REQUEST['path'])) {
            $path = $_REQUEST['path'];
        }
        if ($path !== null) {
            return str_replace($this->clientSeparator, DIRECTORY_SEPARATOR, urldecode(iconv('UTF-8', 'CP1251', $path)));
        }
    }

    protected function encodePath($data)
    {
        if (ArrayHelper::isAssociative($data)) {
            $data['id'] = str_replace(DIRECTORY_SEPARATOR, $this->clientSeparator, iconv('CP1251', 'UTF-8', $data['id']));
        } else {
            foreach ($data as &$item) {
                $item = $this->encodePath($item);
            }
        }

        return $data;
    }

    protected function getCloudPath()
    {
        if ($this->targetId === null || $this->targetType === null) {
            $folder = trim(substr($_SERVER["HTTP_REFERER"], strlen($_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"])), '/');
            $folder = str_replace('/edit', '', $folder);
        } else {
            $folder = $this->targetType . DIRECTORY_SEPARATOR . $this->targetId;
        }
        return $this->settings(static::CONFIG_BASE_CLOUD_PATH) . DIRECTORY_SEPARATOR . $folder;
    }

    protected function fileManager()
    {
        if ($this->_fileManager === null) {
            $this->_fileManager = new RestFileManager($this->getCloudPath());
        }

        return $this->_fileManager;
    }

    protected function settings($key = null)
    {
        if ($this->_settings === null) {
            $config = App::getConfig();
            $this->_settings = $config['modules']['filemanager'];
        }
        if ($key === null) {
            return $this->_settings;
        }
        if (array_key_exists($key, $this->_settings)) {
            return $this->_settings[$key];
        }
    }

    public function getResults()
    {
        return $this->run();
    }

    public function body()
    {
        return $this->loadResults();
    }

    public function load(array $props)
    {
        parent::load($props);

        return $this;
    }

    private $_fileManager;
    private $_settings;
}