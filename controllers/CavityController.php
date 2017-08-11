<?php

namespace app\controllers;

use app\models\CavitySupportingDocument;
use Yii;
use app\models\Cavity;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CavityController implements the CRUD actions for Cavity model.
 */
class CavityController extends Controller
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
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete','accept'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ]
        ];
    }

    /**
     * Lists all Cavity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Cavity::find()->andWhere(['NOT',[ 'title'=>null ]])->orderBy(['date_created'=>SORT_DESC])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cavity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $query = CavitySupportingDocument::find()->where(['cavity_form_id'=>$id]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Cavity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cavity();
        $model->save();
        return $this->redirect(Url::to(['/cavity/update', 'id' => $model->id]));
    }

    /**
     * Updates an existing Cavity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $supportingDocument = new CavitySupportingDocument();
        if ($model->load(Yii::$app->request->post())) {
            $dtObj = date_create_from_format("d/m/Y" ,$model->birthday);
            if($dtObj){
                $model->birthday = $dtObj->format("Y-m-d H:i:s");
            }
            $dtObj = date_create_from_format("d/m/Y" ,$model->when_property_moved);
            if($dtObj){
                $model->when_property_moved = $dtObj->format("Y-m-d H:i:s");
            }
            $dtObj = date_create_from_format("d/m/Y" ,$model->CWI_installation_date);
            if($dtObj){
                $model->CWI_installation_date = $dtObj->format("Y-m-d H:i:s");
            }
            $dtObj = date_create_from_format("d/m/Y H:i" ,$model->date_time_callback);
            if($dtObj){
                $model->date_time_callback = $dtObj->format("Y-m-d H:i:s");
            }

            if($model->save()){
                Yii::$app->getSession()->setFlash('success', 'Supporting document created');
            }
//            return $this->refresh();
        }
        /*format the date output */
        $dtObj = date_create_from_format("Y-m-d H:i:s" ,$model->birthday);
        if($dtObj){
            $model->birthday = $dtObj->format("d/m/Y");
        }
        $dtObj = date_create_from_format("Y-m-d H:i:s" ,$model->when_property_moved);
        if($dtObj){
            $model->when_property_moved = $dtObj->format("d/m/Y");
        }
        $dtObj = date_create_from_format("Y-m-d H:i:s" ,$model->CWI_installation_date);
        if ($dtObj) {
            $model->CWI_installation_date = $dtObj->format("d/m/Y");
        }
        $dtObj = date_create_from_format("Y-m-d H:i:s" ,$model->date_time_callback);
        if($dtObj){
            $model->date_time_callback = $dtObj->format("d/m/Y H:i");
        }
        if ($supportingDocument->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            /* save the uploaded file to uploads/supporting_documents folder*/
            $uploadedDocument = UploadedFile::getInstance($supportingDocument, 'document_name');
            $supportingDocument->document_name= uniqid().'-'.$uploadedDocument->name;
            /* save the uploaded document in safe place */
            $supportingDocument->cavity_form_id = intval(@$_GET['cavity_form_id']);
            $supportingDocument->type = Html::encode(@$_GET['type']);
            $finalUploadName = Yii::getAlias('@supporting_document_path') .  DIRECTORY_SEPARATOR.$supportingDocument->document_name;
            $uploadedDocument->saveAs($finalUploadName);

            if ($supportingDocument->save()) {
                if(Yii::$app->request->isAjax){
                    $uploadedDocument = UploadedFile::getInstance($supportingDocument, 'document_name');
                    $supportingDocument->document_name= uniqid().'-'.$uploadedDocument->name;
                    $finalUploadName = Yii::getAlias('@supporting_document_path') .  DIRECTORY_SEPARATOR.$supportingDocument->document_name;
                    $uploadedDocument->saveAs($finalUploadName);
                    if($supportingDocument->save()){
                        return Json::encode([
                            'files' => [
                                [
                                    'name' => $uploadedDocument->name,
                                    'size' => $uploadedDocument->size,
                                ],
                            ],
                        ]);
                    }

                }

            }
            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
            'supportingDocument' => $supportingDocument,
        ]);
    }

    /**
     * Deletes an existing Cavity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->addFlash('success', 'Record deleted');
        return $this->redirect(['index']);
    }


    public function actionAccept($id)
    {
        /* @TODO - create QuestionairePropertyRecord record*/
        /*get cavity record*/
        /*create property record*/
        /*import the images and documents*/
        /*done*/
        Yii::$app->session->addFlash('success', 'The record has been transfered. @TODO');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Cavity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cavity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cavity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
