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
        $defaultQuery = PropertyRecord::find()->orderBy(['date_updated'=>SORT_DESC,'date_created'=>SORT_DESC]);
        $propertRecordModel = new PropertyRecord();
        if(Yii::$app->user->can('Consultant')){
            // filter the query to only the data he/she created
            $defaultQuery->andWhere(['created_by' => \Yii::$app->user->id]);
        }
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
        if (Yii::$app->user->can('Manager')) {
            $userCreated = [];
            $userCreated[] = Yii::$app->user->id;
            $userCreatedByManagerRes = UserCreator::find()
                ->where(['creator_id'=>\Yii::$app->user->id])
                ->asArray()
                ->all();
            foreach ($userCreatedByManagerRes as $currentUserCreatedByManagerRes) {
                $userCreated[] = $currentUserCreatedByManagerRes['agent_id'];
            }
            $filterModel->setQueryObject(PropertyRecord::find());
            $queryObject = $filterModel->getQueryObject();
            $queryObject->andWhere(['in', 'tbl_property_record.created_by', $userCreated]);
            $dataProvider = $filterModel->search();
        }
        if (Yii::$app->user->can('Agent')) {
            $userCreated = [];
            $userCreated[] = Yii::$app->user->id;
            $userCreatedByManagerRes = UserCreator::find()
                ->where(['creator_id'=>\Yii::$app->user->id])
                ->asArray()
                ->all();
            foreach ($userCreatedByManagerRes as $currentUserCreatedByManagerRes) {
                $userCreated[] = $currentUserCreatedByManagerRes['agent_id'];
            }
            $filterModel->setQueryObject(PropertyRecord::find());
            $queryObject = $filterModel->getQueryObject();
            $queryObject->andWhere(['in', 'tbl_property_record.created_by', $userCreated]);
            $dataProvider = $filterModel->search();
        }


        return $this->render('index', [
            'filterModel' => $filterModel,
            'dataProvider'=> $dataProvider,
            'propertRecordModel'=>$propertRecordModel,
            'insulationCollection'=>$insulationCollection,
            'availableUsers'=>$availableUsers,
        ]);
    }

    public function actionTest()
    {
        $creatorIdCollection = [];
        $leadCreatorRetriever = new LeadCreatorRetriever();
        $leadCreatorRetriever->retrieve(Yii::$app->user->id);
        $creatorIdCollection = $leadCreatorRetriever->getLeadCreatorIdCollection();
        var_dump($creatorIdCollection);
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
