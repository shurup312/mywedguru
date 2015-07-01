<?php

namespace webapp\modules\filemanager\controllers;

use webapp\modules\filemanager\assets\FileManagerAsset;
use webapp\modules\filemanager\services\CreateFolderService;
use webapp\modules\filemanager\services\CreateLinkService;
use webapp\modules\filemanager\services\DeleteFileService;
use webapp\modules\filemanager\services\GetListService;
use webapp\modules\filemanager\services\UploadFileService;
use webapp\modules\filemanager\services\GetClientSettings;
use webapp\modules\filemanager\services\GetMemoryInfoService;
use webapp\modules\filemanager\services\ReadFileService;
use system\core\base\View;
use system\core\behaviors\AccessBehavior;
use webapp\modules\users\models\User;

/**
 * File manager REST api
 * Class Controller
 * @package webapp\modules\filemanager\controllers
 */
class Controller extends \system\core\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessBehavior::className(),
                'rights' => [User::ADMIN_RIGHTS],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function initOnce()
    {
        View::setDesign('admin');
        View::setDesignParams([
            'header' => 'Файловый менеджер',
            'js' => '',
        ]);
        FileManagerAsset::init();
    }

    /**
     * Render template and empty content for js application
     * @return string
     */
    public function actionIndex()
    {
        return View::withDesign('index');
    }

    /**
     * REST api entry point
     * @return string
     */
    public function actionApi()
    {
        $this->setParams($_REQUEST);
        $return = null;
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $return = $this->getFileList();
                break;
            case 'POST':
                $this->setParams($this->streamParams());
                if (isset($_GET['type']) && $_GET['type'] == 'link') {
                    $return = $this->createLink();
                } else {
                    $return = $this->createFolder();
                }
                break;
            case 'DELETE':
                $return = $this->delete();
        }

        return $this->responseJson($return);
    }

    /**
     * Read file by http
     * @return mixed
     * @throws \Exception
     */
    public function actionContent()
    {
        try {
            $file = (new ReadFileService())->load($_GET)->getResults();
            header('Content-Type: ' . $file->type);
            header('Content-Length: ' . $file->size);
            header('Content-Disposition: attachment; filename="' . $file->name . '"');
            return $file->content;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 404);
        }
    }

    public function actionPreview()
    {
        try {
            $file = (new ReadFileService())->load($_GET)->getResults();
            header('Content-Type: ' . $file->type);
            header('Content-Length: ' . $file->size);
            readfile($file->path); exit;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 404);
        }
    }

    /**
     * Settings for client application
     * @return string JSON
     */
    public function actionSettings()
    {
        return $this->responseJson((new GetClientSettings())->getResults());
    }

    /**
     * About memory information
     * @return string JSON
     */
    public function actionMemory()
    {
        return $this->responseJson((new GetMemoryInfoService())->getResults());
    }

    /**
     * File uploader
     * @return string result operation
     */
    public function actionUpload()
    {
        try {
            (new UploadFileService())->load(['files' => $_FILES])->getResults();
        } catch (\Exception $e) {
            return $this->responseJson(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get Files | Folders list
     * @return array
     */
    protected function getFileList()
    {
        return (new GetListService())->load($this->getParams())->getResults();
    }

    /**
     * Create new folder
     * @return array
     */
    protected function createFolder()
    {
        return (new CreateFolderService())->load($this->getParams())->getResults();
    }

    /**
     * Create new link
     * @return array
     */
    protected function createLink()
    {
        return (new CreateLinkService())->load($this->getParams())->getResults();
    }

    /**
     * Delete files | folders
     * @return array
     */
    public function delete()
    {
        return (new DeleteFileService())->load($this->getParams())->getResults();
    }

    /**
     * Response JSON format
     * @param $data
     * @return string
     */
    protected function responseJson($data)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }

        return (json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Setter params
     * @param $value
     */
    protected function setParams($value)
    {
        $this->_params = $value;
    }

    /**
     * Getter params
     * @param null $key
     * @return array
     */
    protected function getParams($key = null)
    {
        if ($key !== null && array_key_exists($key, $this->_params)) {
            if (is_object($this->_params)) {
                return $this->_params->$key;
            } else {
                return $this->_params[$key];
            }
        }

        return $this->_params;
    }

    /**
     * Params from php std input
     * @return mixed
     */
    protected function streamParams()
    {
        return (array) json_decode(file_get_contents('php://input'));
    }

    private $_params = [];
}