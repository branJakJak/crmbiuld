<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
//defined('YII_ENV') or define('YII_ENV', 'prod');



require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();
$config = require(__DIR__ . '/../config/web.php');
$app = new yii\web\Application($config);
Yii::setAlias("@upload_document_path",Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents');
Yii::setAlias("@upload_image_path",Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images');
Yii::setAlias("@triage_path",Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'triage');
Yii::setAlias("@supporting_document_path",Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'supporting_document_path');
$app->run();

