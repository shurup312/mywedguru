<?php
$params = array_merge(require(__DIR__.'/../../common/config/params.php'), require(__DIR__.'/../../common/config/params-local.php'), require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php'));
return [
    'id'                  => 'app-frontend',
    'name' => 'Школа бортпроводников',
    'language'       => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'basePath'            => '/',
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components'          => [
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
    ],
    'params'              => $params,
];
