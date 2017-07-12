<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'CRM Build',
    'name' => 'CRM Build',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'bE4jHIZlsBvoS6QDRiyvbg7UGTEbYEA3',
            'enableCsrfValidation'=>true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => array(
                '/site/logout' => '/user/logout',
                '/record/view/<id:\d+>' => '/record/view',
                '/record/update/<id:\d+>' => '/record/update',
                '/owner/delete/<id:\d+>' => '/owner/delete',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],        
        'db' => require(__DIR__ . '/db-prod.php'),
    ],
    'params' => $params,
];


if (YII_ENV === 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    $config['components']['db'] = require(__DIR__ . '/db.php');
}

/**
 * Yii2 user module
 */
$config['modules']['user'] = [
    'class' =>  'dektrium\user\Module',
    'enableConfirmation' => false,
    'admins'=>['admin'],
    'controllerMap' => [
        'registration' => [
            'class' => \dektrium\user\controllers\RegistrationController::className(),
            'layout' => '@app/views/layouts/main-login.php',
        ],
    ],
];


$config['modules']['gridview'] = [
    'class' => \kartik\grid\Module::className()
];
return $config;
