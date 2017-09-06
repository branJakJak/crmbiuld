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
use yii\web\UnauthorizedHttpException;

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
                'only' => ['index', 'create', 'update', 'update-profile', 'info', 'switch', 'assignment', 'delete', 'block', 'resend-password'],
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['switch'],
//                        'roles' => ['@'],
//                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'update-profile', 'info', 'switch', 'assignment', 'delete', 'block', 'resend-password'],
                        'roles' => ['admin', 'Admin', 'Manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update','update-profile','index'],
                        'roles' => ['Agent'],
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
        $searchModel = \Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        if ( !\Yii::$app->user->can('Admin') && !\Yii::$app->user->can('admin')) {
            /*get all user that this user created*/
            $createrUsers = [];
            $userIds = UserCreator::find()->where(['creator_id' => \Yii::$app->user->id])->asArray()->all();
            foreach ($userIds as $currUserCreated) {
                $createrUsers[] = $currUserCreated['agent_id'];
            }
            $newQuery = User::find();
            $newQuery->andWhere(['in', 'id', $createrUsers]);
            $dataProvider->query = $newQuery;
        } else {
            $newQuery = User::find();
            $dataProvider->query = $newQuery;
        }
//        $query = $dataProvider->query;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Updates an existing User model.
     * @param int $id
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate($id)
    {
        // check if Agent , and the current user $id , is his/her creation
        $creatorId = \Yii::$app->user->id;
        $childId = $id;
        if (\Yii::$app->user->can('Consultant')) {
            if (!UserCreator::isOwner($creatorId, $childId)) {
                throw new UnauthorizedHttpException();
            }
        }
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Account details have been updated'));
            $this->trigger(self::EVENT_AFTER_UPDATE, $event);
            return $this->refresh();
        }

        return $this->render('_account', [
            'user' => $user,
        ]);
    }

}