<?php
$params = array_merge(require(__DIR__.'/../../common/config/params.php'), require(__DIR__.'/../../common/config/params-local.php'),
    require(__DIR__.'/params.php'), require(__DIR__.'/params-local.php'));
use \yii\web\Request;

$baseUrl = str_replace('/application/frontend/web', '', (new Request)->getBaseUrl());
return [
    'id'                  => 'app-frontend',
    'name'                => 'Школа бортпроводников',
    'language'            => 'ru-RU',
    'sourceLanguage'      => 'ru-RU',
    'aliases'             => [
        '@cabinet'     => '@app/modules/cabinet',
        '@auth'        => '@app/modules/auth',
        '@userDetails' => '@app/modules/userDetails',
    ],
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
        'request'      => [
            'baseUrl' => $baseUrl,
        ],
        'user'         => [
            'identityClass'   => 'infrastructure\person\entities\User',
            'enableAutoLogin' => true,
            'loginUrl'        => '/auth/',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
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
                'auth/step1/<userType:\d+>'  => 'auth/default/step1',
                'auth/<value:\w+>'           => 'auth/default/<value>',
                'cabinet/save-price'         => 'cabinet/default/save-price',
                'cabinet/default/save-price' => 'cabinet/default/save-price',
                'cabinet/edit'               => 'cabinet/default/edit',
                'cabinet/default/edit'       => 'cabinet/default/edit',
                'cabinet/<slug:\S+>'         => 'cabinet/default/index',
            ],
        ],
    ],
    'modules'             => [
        'auth'        => [
            'class' => 'auth\Module',
        ],
        'cabinet'     => [
            'class' => 'cabinet\Module',
        ],
        'userDetails' => [
            'class' => 'userDetails\Module',
        ],
    ],
    'params'              => $params,
];
