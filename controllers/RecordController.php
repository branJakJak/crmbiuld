<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 5:15 PM
 */

namespace app\controllers;


use app\models\Owner;
use app\models\PropertyDocuments;
use app\models\PropertyNotes;
use app\models\PropertyOwner;
use app\models\PropertyPreAppraisalImages;
use app\models\PropertyRecord;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class RecordController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','update'],
                'rules' => [
                    [
                        'actions' => ['create','update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionCreate()
    {
        /*Property Records*/
        $preCreatedRecord = new PropertyRecord();

        if($preCreatedRecord->load(\Yii::$app->request->post())){
            $preCreatedRecord->created_by = Yii::$app->user->id;
            $preCreatedRecord->save();
            \Yii::$app->session->set("success", "Record saved");
            $this->redirect(["/record/update/", 'id' => $preCreatedRecord->id]);
        }
        return $this->render('create', [
            'preCreatedRecord'=>$preCreatedRecord,
        ]);
    }

    public function actionUpdate($id){
        /* @var $propertyRecord PropertyRecord */
        /*property record*/
        $propertyRecord = PropertyRecord::findOne(['id' => $id]);
        if(!$propertyRecord){
            return new NotFoundHttpException('Sorry that record doesnt exists');
        }
        if ($propertyRecord->load(\Yii::$app->request->post())) {
            $dtTemp = new DateTime($propertyRecord->date_of_cwi);
            $propertyRecord->date_of_cwi = $dtTemp->format("Y-m-d H:i:s");
            $dtTemp = new DateTime($propertyRecord->date_guarantee_issued);
            $propertyRecord->date_guarantee_issued = $dtTemp->format("Y-m-d H:i:s");
//            $propertyRecord->created_by = Yii::$app->user->getId();
            if ($propertyRecord->save()) {
                Yii::$app->getSession()->setFlash('success', 'Property detail updated');
            }
            $this->refresh("#w18-tab0");
        }
        $dtTemp = new DateTime($propertyRecord->date_of_cwi);
        $propertyRecord->date_of_cwi = $dtTemp->format("m/d/Y");
        $dtTemp = new DateTime($propertyRecord->date_guarantee_issued);
        $propertyRecord->date_guarantee_issued = $dtTemp->format("m/d/Y");

        /*property owner*/
        $owner = new Owner();
        if ($owner->load(Yii::$app->request->post())) {
            if ($owner->save()) {
                Yii::$app->getSession()->setFlash('success', 'New property owner added');
                $propertyOwner = new PropertyOwner();
                $propertyOwner->property_id = $propertyRecord->id;
                $propertyOwner->owner_id = $owner->id;
                $propertyOwner->save();
                $owner = new Owner();//clear attribs
            }
            $this->refresh("#w5-tab0");

        }
        /*property documents*/
        $propertyDocument = new PropertyDocuments();
        $propertyDocumentDataProvider = new ActiveDataProvider([
            'query' => PropertyDocuments::find()->where(['property_id'=>$propertyRecord->id])->orderBy(['date_created'=>SORT_DESC])
        ]);
        if ($propertyDocument->load(\Yii::$app->request->post())) {
            $uploadedDocument = UploadedFile::getInstance($propertyDocument, 'document_name');
            $propertyDocument->property_id = $propertyRecord->id;
            $propertyDocument->document_name = uniqid().'-'.$uploadedDocument->name;
            $propertyDocument->uploaded_by = Yii::$app->user->id;
            /* save the uploaded document in safe place */
            $finalUploadName = Yii::getAlias('@upload_document_path') .  DIRECTORY_SEPARATOR.$propertyDocument->document_name;
            $uploadedDocument->saveAs($finalUploadName);
            if($propertyDocument->save()){
                if(Yii::$app->request->isAjax){
                    return Json::encode([
                        'files' => [
                            [
                                'name' => $uploadedDocument->name,
                                'size' => $uploadedDocument->size,
                            ],
                        ],
                    ]);
                }
            }else{
                \Yii::$app->session->set("error", Html::errorSummary($propertyDocument));
            }

        }
        /*Pre appraisal images*/
        $preappraisalImageDataProvider = new ActiveDataProvider(['query' => PropertyPreAppraisalImages::find()->where(['property_id'=>$propertyRecord->id])]);
        $preappraisalImage = new PropertyPreAppraisalImages();
        if ($preappraisalImage->load(\Yii::$app->request->post())) {
            /*@save the uploaded file to a safe place*/
            $uploadedDocument = UploadedFile::getInstance($preappraisalImage, 'image_name');
            $preappraisalImage->property_id = $propertyRecord->id;
            $preappraisalImage->image_name = uniqid().'-'.$uploadedDocument->name;
            /* save the uploaded document in safe place */
            $finalUploadName = Yii::getAlias('@upload_image_path') .  DIRECTORY_SEPARATOR.$preappraisalImage->image_name;
            $uploadedDocument->saveAs($finalUploadName);
            /*save and return json*/
            if ($preappraisalImage->save()) {
                if(Yii::$app->request->isAjax){
                    return Json::encode([
                        'files' => [
                            [
                                'name' => $uploadedDocument->name,
                                'size' => $uploadedDocument->size,
                            ],
                        ],
                    ]);
                }
            }else{
                \Yii::$app->session->set("error", Html::errorSummary($preappraisalImage));
            }
        }
        /*property notes*/
        $propertyNote = new PropertyNotes();
        $propertyNotesDataProvider = new ActiveDataProvider([
            'query' => PropertyNotes::find()->where(['property_id'=>$propertyRecord->id])->orderBy(['date_created'=>SORT_DESC])
        ]);
        if ($propertyNote->load(\Yii::$app->request->post())) {
            Yii::info(VarDumper::dumpAsString(Yii::$app->user->id).' userid is','application');
            $propertyNote->property_id = $propertyRecord->id;
            $propertyNote->created_by = Yii::$app->user->id;
            if($propertyNote->save()){
                \Yii::$app->session->set("success","New property note added" );

                $propertyNote = new PropertyNotes();

            }else{
                \Yii::$app->session->set("error", Html::errorSummary($propertyNote));
            }
            $this->refresh("#w18-tab4");
        }
        $propertyOwnerDataProvider = new ActiveDataProvider(['query' => PropertyOwner::find()->where(['property_id'=>$propertyRecord->id])]);

        return $this->render('update', [
            'propertyRecord' => $propertyRecord,
            'propertyDocument' => $propertyDocument,
            'preappraisalImage' => $preappraisalImage,
            'preappraisalImageDataProvider' => $preappraisalImageDataProvider,
            'propertyNote' => $propertyNote,
            'propertyNotesDataProvider' => $propertyNotesDataProvider,
            'owner' => $owner,
            'propertyOwnerDataProvider' => $propertyOwnerDataProvider,
            'propertyDocumentDataProvider' => $propertyDocumentDataProvider
        ]);
    }

}