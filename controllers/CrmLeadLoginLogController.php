<?php

namespace app\controllers;

use dektrium\user\models\User;
use Dompdf\Exception;
use Yii;
use app\models\CrmLeadLoginLog;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CrmLeadLoginLogController implements the CRUD actions for CrmLeadLoginLog model.
 */
class CrmLeadLoginLogController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'update-profile', 'info', 'switch', 'assignment', 'delete', 'block', 'resend-password'],
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    public function actionLog($username)
    {
        /* @var $userFound User */
        Yii::$app->response->format = Response::FORMAT_JSON;
        $crmLeadLoginLog = new CrmLeadLoginLog();
        //check if user exists
        $userFound = User::find()->where(['username' => $username])->one();
        if ($userFound) {
            /*check if crmlead log exists for this user*/
            $crmLeadLoginLog->user_id = $userFound->id;
            $crmLeadLoginLog->other_information = Html::encode(Yii::$app->request->post('other_information', ''));
            if(!$crmLeadLoginLog->save()){
                throw new Exception((Html::errorSummary($crmLeadLoginLog)));
            }else{
                return [
                    'status'=>'success',
                    'message'=>'Record saved',
                    'log'=>$crmLeadLoginLog->id
                ];
            }
        }
        throw new NotFoundHttpException('That username doesnt exists');
    }

    /**
     * Lists all CrmLeadLoginLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CrmLeadLoginLog::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CrmLeadLoginLog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CrmLeadLoginLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CrmLeadLoginLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CrmLeadLoginLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CrmLeadLoginLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrmLeadLoginLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmLeadLoginLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CrmLeadLoginLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
