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
use app\models\PropertyImages;
use app\models\PropertyNotes;
use app\models\PropertyOwner;
use app\models\PropertyPreAppraisalImages;
use app\models\PropertyRecord;
use app\models\QuestionairePropertyRecord;
use app\models\Triage;
use app\models\TriageNote;
use app\models\UserCreator;
use DateTime;
use dektrium\user\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;
use ZipArchive;

class RecordController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','update','downloadDocument','transferToTriage'],
                'rules' => [
                    [
                        'actions' => ['create','downloadDocument','transferToTriage'],
                        'allow' => true,
                        'roles' => ['admin','Admin'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['Manager','Consultant','Senior Manager'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['admin','Admin','Manager','Agent','Senior Manager'],
                    ]
                ],
            ],
        ];
    }

    /**
     * @param $record_id
     * @return $this
     * @throws NotFoundHttpException
     * @internal param PropertyRecord $propertyRecord
     * @internal param PropertyRecord $currentPropertyDocument
     */
    public function actionDownloadDocument($record_id){

        //get property record , if it exists
        $propertyRecord = PropertyRecord::findOne($record_id);
        if($propertyRecord){
            // get all documents by this property record
            $propertyDocuments = $propertyRecord->getPropertyDocuments()->all();
            $tempArchiveFile = sprintf("Record-%s-%s-%s.zip", $record_id, date("Y-m-d"), uniqid());
            $tempArchiveFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $tempArchiveFile;
            touch($tempArchiveFile);
            $zipArchive = new \ZipArchive();
            $zipArchive->open($tempArchiveFile, \ZipArchive::CREATE);
            foreach ($propertyDocuments as $currentPropertyDocument){
                $documentFileName = $currentPropertyDocument->document_name;
                $documentFileLocation = Yii::getAlias("@upload_document_path").DIRECTORY_SEPARATOR.$documentFileName;
                $zipArchive->addFile($documentFileLocation, $documentFileName);
            }
            $zipArchive->close();
            // put to zip file
            // done
            return Yii::$app->response->sendFile($tempArchiveFile);
        }else{
            //throw exception if it doesn't exists
            throw new NotFoundHttpException("Record doesnt exists");
        }
    }

    /**
     * @param $record_id
     * @return $this
     * @throws NotFoundHttpException
     */
    public function actionDownloadImages($record_id)
    {
        /**
         * @var $propertyRecord PropertyRecord
         * @var $currentPropertyImages PropertyPreAppraisalImages
         */
        //get property record , if it exists
        $propertyRecord = PropertyRecord::findOne($record_id);
        if($propertyRecord){
            // get all documents by this property record
            $propertyImages = $propertyRecord->getPropertyPreAppraisalImages()->all();
            $tempArchiveFile = sprintf("Record-%s-%s-%s.zip", $record_id, date("Y-m-d"), uniqid());
            $tempArchiveFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $tempArchiveFile;
            touch($tempArchiveFile);
            $zipArchive = new ZipArchive();
            $zipArchive->open($tempArchiveFile, ZipArchive::CREATE);
            foreach ($propertyImages as $currentPropertyImages){
                $documentFileName = $currentPropertyImages->image_name;
                $documentFileLocation = Yii::getAlias("@upload_image_path").DIRECTORY_SEPARATOR.$documentFileName;
                $zipArchive->addFile($documentFileLocation, $documentFileName);
            }
            $zipArchive->close();
            // put to zip file
            // done
            return Yii::$app->response->sendFile($tempArchiveFile);
        }else{
            //throw exception if it doesn't exists
            throw new NotFoundHttpException("Record doesnt exists");
        }

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
            new NotFoundHttpException('Sorry that record doesnt exists');
        }
        $isOwner = Yii::$app->user->id != $propertyRecord->created_by;
        $isSubordinate = UserCreator::isSubordinate(Yii::$app->getUser()->id, $propertyRecord->created_by);

        if(!$isOwner && !$isSubordinate){
            throw new ForbiddenHttpException();
        }

        if (
            (
                Yii::$app->user->can('Manager') || 
                Yii::$app->user->can('Consultant') || 
                Yii::$app->user->can('Agent')
            ) 
            && Yii::$app->request->isPost) {
            throw new UnauthorizedHttpException("You are not allowed to edit this record");
        }

//        if ( Yii::$app->user->can('Consultant')) {
//            if(!Yii::$app->user->can('editOwnRecordPermission',['property_record' => $propertyRecord])) {
//                throw new UnauthorizedHttpException("You are not allowed to edit this record");
//            }
//        }


        if ($propertyRecord->load(\Yii::$app->request->post())) {
            $dtTemp = new DateTime($propertyRecord->date_of_cwi);
            $propertyRecord->date_of_cwi = $dtTemp->format("Y-m-d H:i:s");
            $dtTemp = new DateTime($propertyRecord->date_guarantee_issued);
            $propertyRecord->date_guarantee_issued = $dtTemp->format("Y-m-d H:i:s");
            if ($propertyRecord->save()) {
                Yii::$app->getSession()->setFlash('success', 'Record updated');
            }
            return $this->refresh("#basicInformationTab");
        }
        if (isset($propertyRecord->date_of_cwi)) {
            $dtTemp = new DateTime($propertyRecord->date_of_cwi);
            $propertyRecord->date_of_cwi = $dtTemp->format("m/d/Y");
        }
        if ($propertyRecord->date_guarantee_issued) {
            $dtTemp = new DateTime($propertyRecord->date_guarantee_issued);
            $propertyRecord->date_guarantee_issued = $dtTemp->format("m/d/Y");
        }

        /*property owner*/
        $owner = new Owner();
        $owner->postalcode = $propertyRecord->postcode;
        $owner->address1 = $propertyRecord->address1;
        $owner->address2 = $propertyRecord->address2;
        $owner->address3 = $propertyRecord->address3;
        $owner->town = $propertyRecord->town;
        $owner->country = $propertyRecord->country;
        if ($owner->load(Yii::$app->request->post())) {
            if(isset($owner->date_of_birth) && !empty($owner->date_of_birth)){
                $owner->date_of_birth =date_create_from_format("d-M-Y" , $owner->date_of_birth);
                $owner->date_of_birth = $owner->date_of_birth->format("Y-m-d H:i:s");
            }
            if ($owner->save()) {
                Yii::$app->getSession()->setFlash('success', 'New property owner added');
                $propertyOwner = new PropertyOwner();
                $propertyOwner->property_id = $propertyRecord->id;
                $propertyOwner->owner_id = $owner->id;
                $propertyOwner->save();
                $owner = new Owner();//clear attribs
            }


            return $this->refresh("#ownersTab");
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
        $propertPreappraisalImageQuery = PropertyPreAppraisalImages::find()->where(['property_id'=>$propertyRecord->id]);
        $propertPreappraisalImageQuery->andWhere(['<>','image_name','null']);

        $preappraisalImageDataProvider = new ActiveDataProvider(['query' => $propertPreappraisalImageQuery]);
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
        $triageNotesQueryProvider = PropertyNotes::find()
            ->where(['property_id' => $propertyRecord->id ,'note_type'=>PropertyNotes::NOTE_TYPE_TRIAGE])
            ->orderBy(['date_created' => SORT_DESC]);
        $triageNotesDataProvider = new ActiveDataProvider([
            'query' =>$triageNotesQueryProvider
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
            return $this->refresh("#w27-tab5");
        }
        $propertyOwnerDataProvider = new ActiveDataProvider(['query' => PropertyOwner::find()->where(['property_id'=>$propertyRecord->id])]);
        $triageDocument = new Triage();
        if ($triageDocument->load(Yii::$app->request->post())) {
            $uploadedTriageDocument = UploadedFile::getInstance($triageDocument, 'material_file_name');
            $triageDocument->property_record = $propertyRecord->id;
            $triageDocument->material_file_name = uniqid().'-'.$uploadedTriageDocument->name;
            /* save the uploaded document in safe place */
            $finalUploadName = Yii::getAlias('@triage_path') .  DIRECTORY_SEPARATOR.$triageDocument->material_file_name;
            $uploadedTriageDocument->saveAs($finalUploadName);
            /*save and return json*/
            if ($triageDocument->save()) {
                if(Yii::$app->request->isAjax){
                    return Json::encode([
                        'files' => [
                            [
                                'name' => $uploadedTriageDocument->name,
                                'size' => $uploadedTriageDocument->size,
                            ],
                        ],
                    ]);
                }
            } else{
                \Yii::$app->session->set("error", Html::errorSummary($triageDocument));
            }
        }

        $triageDocumentDataProvider = new ActiveDataProvider(['query' => Triage::find()->where(['property_record'=>$propertyRecord->id])]);

        $propertyImagesDataProvider = new ActiveDataProvider(['query' => PropertyImages::find()->where(['property_id'=>$propertyRecord->id])]);


        return $this->render('update', [
            'propertyRecord' => $propertyRecord,
            'propertyDocument' => $propertyDocument,
            'preappraisalImage' => $preappraisalImage,
            'preappraisalImageDataProvider' => $preappraisalImageDataProvider,
            'propertyNote' => $propertyNote,
            'propertyNotesDataProvider' => $propertyNotesDataProvider,
            'owner' => $owner,
            'propertyOwnerDataProvider' => $propertyOwnerDataProvider,
            'propertyDocumentDataProvider' => $propertyDocumentDataProvider,
            'triageDocumentDataProvider' => $triageDocumentDataProvider,
            'triageDocument' => $triageDocument,
            'propertyImagesDataProvider' => $propertyImagesDataProvider,
            'triageNotesDataProvider' => $triageNotesDataProvider
        ]);
    }
    public function actionTransferToTriage($propertyId){
        if (isset($_POST['selection']) && !empty($_POST['selection'])) {
            $preAppraisalImages = PropertyPreAppraisalImages::find()->where(['in', 'id', array_values($_POST['selection'])])->all();
            $uploadImagePath = Yii::getAlias("@upload_image_path");
            $triagePath = Yii::getAlias("@triage_path");
            $successMessage = "";
            $warningMessage = "";
            $numProcessedFile = 0;
            /* @var $currentPreAppraisalImage PropertyImages */
            foreach ($preAppraisalImages as $currentPreAppraisalImage) {
                $fileToCopy = $uploadImagePath . DIRECTORY_SEPARATOR . $currentPreAppraisalImage->image_name;
                $destination = $triagePath . DIRECTORY_SEPARATOR . $currentPreAppraisalImage->image_name;
                /*check if preappraisalimage is already in triage ; if not proceed*/
                if(!Triage::find()->where(['material_file_name' => $currentPreAppraisalImage->image_name])->exists()){
                    copy($fileToCopy, $destination);
                    $triageFile = new Triage();
                    $triageFile->property_record = intval($_POST['property_record']);
                    $triageFile->material_file_name = $currentPreAppraisalImage->image_name;
                    if ($triageFile->save()) {
                        $numProcessedFile++;
                    }
                } else {
                    $warningMessage .= sprintf("%s was already transfered", $currentPreAppraisalImage->image_name);
                }
                //remove the preappraisal
                $currentPreAppraisalImage->delete();
            }

            Yii::$app->getSession()->setFlash('success', sprintf("%s record(s) transfered" , $numProcessedFile));
            if (isset($warningMessage) && !empty($warningMessage)) {
                Yii::$app->getSession()->setFlash('warning', $warningMessage);
            }

        }
        return $this->redirect(Yii::$app->request->referrer.'#w22-tab2');
    }
    public function actionDelete($id)
    {
        $modelFound = $this->findModel($id);
        if ($modelFound) {
            /*delete cavitypropertyrecord*/
            $qp = QuestionairePropertyRecord::find()->where(['property_record_id' => $id]);
            if ($qp->exists()) {
                /* @var $obj QuestionairePropertyRecord*/
                $obj = $qp->one();
                $obj->cavityForm->delete();
                $obj->delete();
            }
            $modelFound->delete();
            Yii::$app->session->addFlash('success', 'Record deleted');
            return $this->redirect(\Yii::$app->getRequest()->getReferrer());
        } else {
            throw new NotFoundHttpException("Cant find record");
        }
    }
    /**
     * Finds the PropertyRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PropertyRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}