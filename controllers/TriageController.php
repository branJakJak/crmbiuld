<?php

namespace app\controllers;

use app\models\PropertyRecord;
use Yii;
use app\models\Triage;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TriageController implements the CRUD actions for Triage model.
 */
class TriageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','update','delete','download'],
                'rules' => [
                    [
                        'actions' => ['index','view','create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update','delete','download'],
                        'allow' => true,
                        'roles' => ['admin','Admin'],
                    ],

                ]
            ]
        ];

    }

    /**
     * Lists all Triage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Triage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Triage model.
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
     * Creates a new Triage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Triage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Triage model.
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
     * Deletes an existing Triage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionDownload($record_id){
        //get property record , if it exists
        $propertyRecord = PropertyRecord::findOne($record_id);
        if($propertyRecord){
            // get all documents by this property record
            $triageDocuments = Triage::find()->where(['property_record' => $record_id])->all();
            $tempArchiveFile = sprintf("Triage-document-%s-%s-%s.zip", $record_id, date("Y-m-d"), uniqid());
            $tempArchiveFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $tempArchiveFile;
            touch($tempArchiveFile);
            $zipArchive = new \ZipArchive();
            $zipArchive->open($tempArchiveFile, \ZipArchive::CREATE);

            /* @var $currentTriageDocument Triage*/
            foreach ($triageDocuments as $currentTriageDocument){
                $documentFileName = $currentTriageDocument->material_file_name;
                $documentFileLocation = Yii::getAlias("@triage_path").DIRECTORY_SEPARATOR.$documentFileName;
                $zipArchive->addFile($documentFileLocation, $documentFileName);
            }
            $zipArchive->close();
            // put to zip file
            // done
            return Yii::$app->response->sendFile($tempArchiveFile);
        }else{
            //throw exception if it doesn't exists
            throw new NotFoundHttpException("Property record doesn't exists");
        }

    }

    /**
     * Finds the Triage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Triage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Triage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
