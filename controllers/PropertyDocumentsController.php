<?php

namespace app\controllers;

use Yii;
use app\models\PropertyDocuments;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertyDocumentsController implements the CRUD actions for PropertyDocuments model.
 */
class PropertyDocumentsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view','create','download','update','delete'],
                'rules' => [
                    [
                        'actions' => ['index','view','create','download'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','update','delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }
    /**
     * Lists all PropertyDocuments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PropertyDocuments::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PropertyDocuments model.
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
     * Creates a new PropertyDocuments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PropertyDocuments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDownload($property)
    {
        /* @var $propertyDocument PropertyDocuments*/
        $propertyDocument = PropertyDocuments::find()->where(['id' => $property])->one();
        if ($propertyDocument) {
            $upload_document_path = Yii::getAlias('@upload_document_path').DIRECTORY_SEPARATOR.$propertyDocument->document_name;
            return Yii::$app->response->sendFile($upload_document_path);
        }
        throw new NotFoundHttpException();
    }

    /**
     * Updates an existing PropertyDocuments model.
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
     * Deletes an existing PropertyDocuments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $modelFound = $this->findModel($id);
        if ($modelFound) {
            $modelFound ->delete();
            $uploadDocPath = Yii::getAlias('@upload_document_path') . DIRECTORY_SEPARATOR . $modelFound->document_name;
            unlink($uploadDocPath);
            Yii::$app->session->set('success', 'Record deleted');
        }else{
            throw new NotFoundHttpException("Document doesnt exists");
        }
        return $this->redirect(Yii::$app->request->referrer.'#w18-tab1');
    }

    /**
     * Finds the PropertyDocuments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PropertyDocuments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyDocuments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
