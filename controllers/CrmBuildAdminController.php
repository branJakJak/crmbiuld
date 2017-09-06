<?php

namespace app\controllers;

use app\components\TriagePdfExport;
use app\models\Cavity;
use app\models\PropertyNotes;
use app\models\PropertyRecord;
use app\models\QuestionairePropertyRecord;
use app\models\Triage;
use app\models\UserCreator;
use dektrium\user\controllers\AdminController;
use dektrium\user\filters\AccessRule;
use dektrium\user\models\User;
use dektrium\user\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CrmBuildAdminController extends AdminController
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'resend-password' => ['post'],
                    'block' => ['post'],
                    'switch' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['switch'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin', 'Admin', 'Manager'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel  = \Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if (\Yii::$app->user->can('Manager')) {
            /*get all user that this user created*/
            $createrUsers = [];
            $userIds = UserCreator::find()->where(['creator_id' => \Yii::$app->user->id])->asArray()->all();
            foreach ($userIds as $currUserCreated) {
                $createrUsers[] = $currUserCreated['agent_id'];
            }
            $newQuery = User::find();
            $newQuery->andWhere(['in','id',  $createrUsers  ]);
            $dataProvider->query = $newQuery;

        }
//        $query = $dataProvider->query;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
    
}