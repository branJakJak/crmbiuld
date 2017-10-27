<?php

$params = require(__DIR__ . '/params.php');


$config = [
    'id' => 'CRM Build',
    'name' => 'CRM Build',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'leadCreatorRetriever' => [
            'class' => 'app\components\LeadCreatorRetriever'
        ],
        'leadChangeNotifier' => [
            'class' => 'app\components\LeadChangeNotifier',
            'config' => [
                'Pending Surveyors Approval' => "app.lead_change_notify",
                'More Info' => "app.lead_change_notify.more_info",
                'Approved By Surveyor and Triage Complete' => "app.lead_change_notify.approved_by_surveyor_and_triage_complete",
                'Land Reg Checks required / CFA booking in process' => "app.lead_change_notify.land_reg_checks_done_waiting_CFA_booking",
                'CFA Complete' => "app.lead_change_notify.cfa_complete",
                'Accepted on System' => "app.lead_change_notify.system_accepted"
            ],
        ],
        'newLeadNotifier' => [
            'class' => 'app\components\NewLeadNotifier',
            'emailsToNotify' => ['rogbennett@me.com', 'clients@whitecollarlegalandadmin.com'],
        ],
        'settings' => [
            'class' => 'pheme\settings\components\Settings'
        ],
        'triageExporter' => [
            'class' => 'app\components\TriagePdfExport'
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'bE4jHIZlsBvoS6QDRiyvbg7UGTEbYEA3',
            'enableCsrfValidation' => false,
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
            'useFileTransport' => false,
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
                '/not-submitted/<id:\d+>' => '/cavity/view',
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
    'class' => 'dektrium\user\Module',
    'enableImpersonateUser' => true,
    'enableRegistration' => false,
    'enableConfirmation' => false,
    'admins' => ['admin'],
    'mailer' => [
        'sender' => 'admin@whitecollarclaim.co.uk',
    ],
    'controllerMap' => [
        'registration' => [
            'class' => \dektrium\user\controllers\RegistrationController::className(),
            'layout' => '@app/views/layouts/main-login.php',
        ],
        'admin' => [
            'class' => "app\controllers\CrmBuildAdminController",
            'on afterUpdate' => function ($event) {
                /* @var $event \dektrium\user\events\UserEvent */
                /* @var $currentUserRole \yii\rbac\Role */
                /* drop all roles attached to current user */
                $currentUser = $event->getUser();
                $userRoles = Yii::$app->authManager->getRolesByUser($currentUser->id);
                foreach ($userRoles as $currentUserRole) {
                    Yii::$app->authManager->revoke($currentUserRole, $currentUser->id);
                }
                if (isset($_POST['role']) && !empty($_POST['role'])) {
                    $newRole = \yii\helpers\Html::encode($_POST['role']);
                    if ($newRoleObj = Yii::$app->authManager->getRole($newRole)) {
                        Yii::$app->authManager->assign($newRoleObj, $currentUser->id);
                    }
                }
            }
        ],
        'security' => [
            'class' => \dektrium\user\controllers\SecurityController::className(),
            'on afterLogin' => function ($model) {
                if (\Yii::$app->user->can('Agent') || \Yii::$app->user->can('Consultant')) {
                    \Yii::$app->getResponse()->redirect(\yii\helpers\Url::to(["/"]), 301)->send();
                    exit(0);
                }
            }
        ],
        'recovery' => [
            'class' => \dektrium\user\controllers\RecoveryController::className(),
            'layout' => '@app/views/layouts/main-login.php',
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
                $currentRole = \yii\helpers\Html::encode($_POST['role']);
                $authManager = Yii::$app->authManager;
                $currentRoleObj = $authManager->getRole($currentRole);
                /* if not exist ; create one */
                if (!$currentRoleObj) {
                    $currentRoleObj = $authManager->createRole($currentRole);
                    $authManager->add($currentRoleObj);
                }

                $userCreator = new \app\models\UserCreator();
                $userCreator->creator_id = Yii::$app->user->id;
                $userCreator->agent_id = $recordCreated->id;
                if (!$userCreator->save()) {
                    //log the error message
                    Yii::error(\yii\helpers\Html::errorSummary($userCreator));
                }

                $cookieJar = tempnam(sys_get_temp_dir(), uniqid());
                /* get nonce id */
                $curlURL = "https://crmlead.whitecollarclaim.co.uk/api/get_nonce/?controller=user&method=register";
                $curlres = curl_init($curlURL);
                curl_setopt($curlres, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlres, CURLOPT_COOKIEFILE, $cookieJar);
                curl_setopt($curlres, CURLOPT_COOKIEJAR, $cookieJar);
                curl_setopt($curlres, CURLOPT_SSL_VERIFYPEER, false);
                $curlResRaw = curl_exec($curlres);
                $dataArr = json_decode($curlResRaw, true);
                curl_close($curlres);


                /* register the data */
                $httpParams = [
                    "username" => Yii::$app->request->post('User')['username'],
                    "email" => Yii::$app->request->post('User')['email'],
                    "user_pass" => Yii::$app->request->post('User')['password'],
                    "nonce" => $dataArr['nonce'],
                    "display_name" => Yii::$app->request->post('User')['username'],
                ];
                $curlURL = "https://crmlead.whitecollarclaim.co.uk/api/user/register?" . http_build_query($httpParams);
                $curlres = curl_init($curlURL);
                curl_setopt($curlres, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlres, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curlres, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curlres, CURLOPT_COOKIEFILE, $cookieJar);
                curl_setopt($curlres, CURLOPT_COOKIEJAR, $cookieJar);
                curl_setopt($curlres, CURLOPT_AUTOREFERER, true);
                curl_setopt($curlres, CURLOPT_HEADER, 1);
                $curlResRaw = curl_exec($curlres);
                curl_close($curlres);


                /* get role and assign the role */
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
$config['modules']['notifyuser'] = [
    'class' => 'app\modules\notifyuser\Module',
];
$config['modules']['settings'] = [
    'class' => 'pheme\settings\Module',
    'sourceLanguage' => 'en'
];


/* Yii2 Pdf Viewer*/
$config['modules']['pdfjs'] = [
    'class' => '\yii2assets\pdfjs\Module'
];


return $config;
