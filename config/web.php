<?php

$params = require(__DIR__ . '/params.php');



$config = [
    'id' => 'CRM Build',
    'name' => 'CRM Build',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'bE4jHIZlsBvoS6QDRiyvbg7UGTEbYEA3',
            'enableCsrfValidation'=>false,
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
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => array(
               '/not-submitted' => '/cavity',
                '/record/view/<id:\d+>' => '/record/view',
                '/record/update/<id:\d+>' => '/record/update',
                '/owner/delete/<id:\d+>' => '/owner/delete',
                '/cavity/accept//<id:\d+>' => '/cavity/accept',
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
        'admin' => [
            'class' => \dektrium\user\controllers\AdminController::className(),
            'on afterUpdate'  => function($event){
                /* @var $event \dektrium\user\events\UserEvent */
                /* @var $currentUserRole \yii\rbac\Role */
                /*drop all roles attached to current user*/
                $currentUser = $event->getUser();
                $userRoles = Yii::$app->authManager->getRolesByUser($currentUser->id);
                foreach ($userRoles as $currentUserRole) {
                    Yii::$app->authManager->revoke($currentUserRole, $currentUser->id);
                }
                if(isset($_POST['role']) && !empty($_POST['role'])){
                    $newRole = \yii\helpers\Html::encode($_POST['role']);
                    if ($newRoleObj = Yii::$app->authManager->getRole($newRole)) {
                        Yii::$app->authManager->assign($newRoleObj, $currentUser->id);
                    }
                }
            }
        ],
        'security'=>[
            'class'=> \dektrium\user\controllers\SecurityController::className(),
            'on afterLogin'=>function($model){
                if (\Yii::$app->user->can('Agent')) {
                    \Yii::$app->getResponse()->redirect(\yii\helpers\Url::to(["/"]),301)->send();
                    exit(0);
                }
            }
        ]
    ],
    'modelMap' => [
        'User' => [
            'class' => \dektrium\user\models\User::className(),
            'on afterCreate' => function ($eventArgs) {
                /**
                 * @var $recordCreated \dektrium\user\models\User
                 * @var $authManager \yii\rbac\PhpManager
                 */
                $recordCreated = $eventArgs->sender;
                $currentRole =\yii\helpers\Html::encode($_POST['role']);
                $authManager = Yii::$app->authManager;
                $currentRoleObj = $authManager->getRole($currentRole);
                /*if not exist ; create one*/
                if(!$currentRoleObj){
                    $currentRoleObj = $authManager->createRole($currentRole);
                    $authManager->add($currentRoleObj);
                }
                /*get role and assign the role */
                $authManager->assign($currentRoleObj, $recordCreated->id);
                Yii::$app->session->addFlash("success", "User $recordCreated->email is assigned as $currentRole ");

            }
        ],
    ]

];
$config['modules']['gridview'] = [
    'class' => \kartik\grid\Module::className()
];

$config['modules']['api'] = [
    'class' => "app\modules\api\Module"
];

return $config;
