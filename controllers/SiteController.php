<?php

namespace app\controllers;

use app\models\FilterPropertyRecordForm;
use app\models\PropertyRecord;
use dektrium\user\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $filterModel = new FilterPropertyRecordForm();
        $defaultQuery = PropertyRecord::find();
        $propertRecordModel = new PropertyRecord();
        $dataProvider = new ActiveDataProvider(['query'=>$defaultQuery]);
        $insulationCollection = PropertyRecord::find()->select('insulation_type')->distinct()->all();
        $availableUsers = User::find()->select('username')->distinct()->all();
        if ($filterModel->load(Yii::$app->request->post())) {
            if ($filterModel->validate()) {
                //search and return the result
                if (isset($_POST['scenario'])) {
                    $filterModel->scenario = $_POST['scenario'];
                }
                $dataProvider = $filterModel->search();
            }
        }
        return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider'=> $dataProvider,
            'propertRecordModel'=>$propertRecordModel,
            'insulationCollection'=>$insulationCollection,
            'availableUsers'=>$availableUsers,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
