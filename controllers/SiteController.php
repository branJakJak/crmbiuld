<?php

namespace app\controllers;

use app\components\LeadCreatorRetriever;
use app\models\FilterPropertyRecordForm;
use app\models\PropertyRecord;
use app\models\UserCreator;
use dektrium\user\models\User;
use pheme\settings\components\Settings;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
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
        /* @var $dataProvider ActiveDataProvider */
        $filterModel = new FilterPropertyRecordForm();
        $propertRecordModel = new PropertyRecord();
        $insulationCollection = PropertyRecord::find()->select('insulation_type')->distinct()->all();
        $availableUsers = User::find()->select('username')->distinct()->all();
        $defaultQuery = PropertyRecord::find()->orderBy(['date_updated' => SORT_DESC, 'date_created' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider(['query' => $defaultQuery]);
        if ($filterModel->load(Yii::$app->request->post())) {
            if ($filterModel->validate()) {
                //search and return the result
                if (isset($_POST['scenario'])) {
                    $filterModel->scenario = $_POST['scenario'];
                }
                $dataProvider = $filterModel->search();
            }
        }
//        if (!Yii::$app->user->can('Admin') &&
//            !Yii::$app->user->can('Senior Manager') &&
//            !Yii::$app->user->can('admin')) {
//            //search leads created by this user and its subordinate
//            $creatorIdCollection = [];
//            $leadCreatorRetriever = new LeadCreatorRetriever();
//            $leadCreatorRetriever->retrieve(Yii::$app->user->id);
//            $creatorIdCollection = $leadCreatorRetriever->getLeadCreatorIdCollection();
//            $defaultQuery->andWhere(['in', 'tbl_property_record.created_by', $creatorIdCollection]);
//            $dataProvider = new ActiveDataProvider(['query' => $defaultQuery]);
//        }
        return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
            'propertRecordModel' => $propertRecordModel,
            'insulationCollection' => $insulationCollection,
            'availableUsers' => $availableUsers,
        ]);
    }

//    public function actionTest()
//    {
//        /* @var $settings Settings */
//        $settings = Yii::$app->settings;
//        $settings->set('app.new_lead_notify', Json::encode([ 'email1@gmail.com' ]));
//        $settings->set('app.lead_change_notify', Json::encode([ 'email1@gmail.com' ]));
//        $new_lead_notify_email = $settings->get('app.new_lead_notify_email');
//        $lead_change_notify_email = $settings->get('app.lead_change_notify');
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
