<?php
$params = array_merge(require(__DIR__.'/../../common/config/params.php'), require(__DIR__.'/../../common/config/params-local.php'), require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php'));
use \yii\web\Request;

$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());
return [
    'id'                  => 'app-frontend',
    'name'                => 'Школа бортпроводников',
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'request'      => [
            'baseUrl' => $baseUrl,
        ],
        'user'         => [
            'identityClass'   => 'frontend\models\User',
            'enableAutoLogin' => true,
            'loginUrl'        => '/auth/',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG?3:0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning'
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'baseUrl'         => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                'auth/step1/<userType:\d+>' => 'auth/default/step1',
                'auth/<value:\w+>'          => 'auth/default/<value>',
            ],
        ],
    ],
    'modules'             => [
        'auth'    => [
            'class' => 'frontend\modules\auth\Module',
        ],
        'cabinet' => [
            'class' => 'app\modules\cabinet\Module',
        ],
        'userDetails' => [
            'class' => 'app\modules\userDetails\Module',
        ],
    ],
    'params'              => $params,
];
