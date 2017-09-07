<?php

namespace app\controllers;

use app\components\LeadCreatorRetriever;
use app\models\FilterPropertyRecordForm;
use app\models\PropertyRecord;
use app\models\UserCreator;
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
        $propertRecordModel = new PropertyRecord();
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
        } else {
            $defaultQuery = PropertyRecord::find()->orderBy(['date_updated' => SORT_DESC, 'date_created' => SORT_DESC]);
            if (!Yii::$app->user->can('Admin') &&
                !Yii::$app->user->can('admin') &&
                !Yii::$app->user->can('Senior Manager')) {
                //search leads created by this user and its subordinate
                $creatorIdCollection = [];
                $leadCreatorRetriever = new LeadCreatorRetriever();
                $leadCreatorRetriever->retrieve(Yii::$app->user->id);
                $creatorIdCollection = $leadCreatorRetriever->getLeadCreatorIdCollection();
                $defaultQuery->andWhere(['in', 'tbl_property_record.created_by', $creatorIdCollection]);
            }
            $dataProvider = new ActiveDataProvider(['query' => $defaultQuery]);
        }


        return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
            'propertRecordModel' => $propertRecordModel,
            'insulationCollection' => $insulationCollection,
            'availableUsers' => $availableUsers,
        ]);
    }
//
//    public function actionTest()
//    {
//        $creatorIdCollection = [];
//        $leadCreatorRetriever = new LeadCreatorRetriever();
//        $leadCreatorRetriever->retrieve(Yii::$app->user->id);
//        $creatorIdCollection = $leadCreatorRetriever->getLeadCreatorIdCollection();
//    }
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
