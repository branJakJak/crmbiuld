<?php

namespace app\controllers;

use app\models\CavitySupportingDocument;
use app\models\Owner;
use app\models\PropertyDocuments;
use app\models\PropertyImages;
use app\models\PropertyOwner;
use app\models\PropertyPreAppraisalImages;
use app\models\PropertyRecord;
use app\models\QuestionairePropertyRecord;
use app\models\UserCreator;
use dektrium\user\models\User;
use Yii;
use app\models\Cavity;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
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
                'only' => ['index', 'view', 'create', 'update', 'delete','accept'],
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
        $defaultQuery = Cavity::find();
        /*can view the leads submitted by him/her or his/her agents*/
        if (Yii::$app->user->can('Manager')) {
            $allowedUsername = [];
            $allowedUsername[] = \Yii::$app->user->identity->username;
            // get the id of created_by_user
            $agentsCreatedByUser = UserCreator::find()->where(['creator_id' => Yii::$app->user->id])->asArray()->all();
            foreach ($agentsCreatedByUser as $currentAllowed) {
                $userObj = User::find()->where(['id'=>$currentAllowed['agent_id']])->one();
                if($userObj){
                    $allowedUsername[] = $userObj->username;
                }
                //find username
            }
            // update query
            $defaultQuery->andWhere(['in', 'created_by_user', $allowedUsername]);
        } else if (Yii::$app->user->can('Agent')) {
            $defaultQuery->andWhere(['in', 'created_by_user', [\Yii::$app->user->identity->username]]);
        }
        else {
            $defaultQuery->leftJoin('tbl_questionaire_property_record', 'tbl_cavity.id = tbl_questionaire_property_record.cavity_form_id');
            $defaultQuery
    //            ->where(['NOT',[ 'tbl_questionaire_property_record.cavity_form_id'=>null ]])
                ->where([ 'tbl_questionaire_property_record.cavity_form_id'=>null ])
                ->orderBy(['tbl_cavity.date_created'=>SORT_DESC]);
        }
        if (Yii::$app->user->can('Consultant')) {
            $defaultQuery->andWhere(['in', 'created_by_user', \Yii::$app->user->identity->username]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $defaultQuery
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cavity model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $query = CavitySupportingDocument::find()->where(['cavity_form_id'=>$id]);
        $curObj =$this->findModel($id);
        if ($curObj && Yii::$app->user->can('Agent')) {
            if ($curObj->created_by_user !== \Yii::$app->user->username) {
                throw new ForbiddenHttpException();
            }
        }

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
                return Json::encode([
                    'files' => [
                        [
                            'name' => $uploadedDocument->name,
                            'size' => $uploadedDocument->size,
                        ],
                    ],
                ]);

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
    public function actionDecline($id)
    {
        /* Create QuestionairePropertyRecord record*/
        /*get cavity record*/
        $modelFound = $this->findModel($id);
        /*create property record*/
        $propertyRecord = new PropertyRecord();
        $propertyRecord->postcode = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->address1 = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->address2 = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->address3 = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->town = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->country = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->installer = $modelFound->CWI_installer;
        
        if(User::find()->where(['username' => $modelFound->created_by_user])->exists()){
            $userModel = User::find()->where(['username' => $modelFound->created_by_user])->one();
            $propertyRecord->created_by = $userModel->id;
        }

        $propertyRecord->status = PropertyRecord::PROPERTY_STATUS_REJECTED;
        $propertyRecord->save();

        /*create owner */
        $owner = new Owner();
        $owner->title = $modelFound->title;
        $owner->firstname = $modelFound->firstname;
        $owner->lastname = $modelFound->lastname;
        $owner->address1 = $modelFound->address1_cavity_installation;
        $owner->address2 = $modelFound->address2_cavity_installation;
        $owner->address3 = $modelFound->address3_cavity_installation;
        $owner->town = $modelFound->address_postcode_cavity_installation;
        $owner->country = "United Kingdom";
        $owner->email_address = $modelFound->email_address;
        $owner->phone_number = $modelFound->telephone_number;
        $owner->date_of_birth = $modelFound->birthday;
        $owner->save();
        $propertOwner = new PropertyOwner();
        $propertOwner->owner_id = $owner->id;
        $propertOwner->property_id = $propertyRecord->id;
        $propertOwner->save();
        /*import the images and documents*/
        $supportingDocuments = $modelFound->getSupportingDocuments()->all();
        foreach ($supportingDocuments as $currentSupportingDocuments) {
            /* @var $currentSupportingDocuments CavitySupportingDocument */

            /*if current document has type supporting_document_images */
            if ($currentSupportingDocuments->type === 'supporting_document_images') {
                /*save to property document */
                $copyFrom = Yii::getAlias('@supporting_document_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                $finalUploadName = Yii::getAlias('@upload_document_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                copy($copyFrom, $finalUploadName);

                $propertyDocument = new PropertyDocuments();
                $propertyDocument->property_id = $propertyRecord->id;
                $propertyDocument->document_name = $currentSupportingDocuments->document_name;
                $propertyDocument->save();
            } else {
                /*save it to pre appraisal images */
                $preAppraisalImage = new PropertyPreAppraisalImages();
                $copyFrom = Yii::getAlias('@supporting_document_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                $finalUploadName = Yii::getAlias('@upload_image_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                copy($copyFrom, $finalUploadName);
                $preAppraisalImage->property_id = $propertyRecord->id;
                $preAppraisalImage->image_name = $currentSupportingDocuments->document_name;
                $preAppraisalImage->save();
            }
            $questionairePropertyRecord = new QuestionairePropertyRecord();
            $questionairePropertyRecord->cavity_form_id = $modelFound->id;
            $questionairePropertyRecord->property_record_id = $propertyRecord->id;
            $questionairePropertyRecord->save();
        }
        return $this->redirect(Url::to(['/record/update', 'id' => $propertyRecord->id]));
    }


    public function actionAccept($id)
    {
        /* Create QuestionairePropertyRecord record*/
        /*get cavity record*/
        $modelFound = $this->findModel($id);
        /*create property record*/
        $propertyRecord = new PropertyRecord();
        $propertyRecord->postcode = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->address1 = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->address2 = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->address3 = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->town = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->country = $modelFound->address_postcode_cavity_installation;
        $propertyRecord->installer = $modelFound->CWI_installer;
        
        if(User::find()->where(['username' => $modelFound->created_by_user])->exists()){
            $userModel = User::find()->where(['username' => $modelFound->created_by_user])->one();
            $propertyRecord->created_by = $userModel->id;
        }

        $propertyRecord->status = PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL;
        $propertyRecord->save();

        /*create owner */
        $owner = new Owner();
        $owner->title = $modelFound->title;
        $owner->firstname = $modelFound->firstname;
        $owner->lastname = $modelFound->lastname;
        $owner->address1 = $modelFound->address1_cavity_installation;
        $owner->address2 = $modelFound->address2_cavity_installation;
        $owner->address3 = $modelFound->address3_cavity_installation;
        $owner->town = $modelFound->address_postcode_cavity_installation;
        $owner->country = "United Kingdom";
        $owner->email_address = $modelFound->email_address;
        if ($modelFound->second_application_telephone) {
            $owner->phone_number = $modelFound->telephone_number.' , '.$modelFound->second_application_mobile_landline;
        } else {
            $owner->phone_number = $modelFound->telephone_number;
        }
        $owner->phone_number = $modelFound->telephone_number;
        $owner->date_of_birth = $modelFound->birthday;
        $owner->save();

        $owner2 = new Owner();
        $owner2->title = $modelFound->second_application_title;
        $owner2->firstname = $modelFound->second_application_firstname;
        $owner2->lastname = $modelFound->second_application_lastname;
        $owner2->address1 = $modelFound->address1_cavity_installation;
        $owner2->address2 = $modelFound->address2_cavity_installation;
        $owner2->address3 = $modelFound->address3_cavity_installation;
        $owner2->date_of_birth = $modelFound->second_application_birthday;
        if (isset($modelFound->second_application_mobile_landline)) {
            $owner2->phone_number = $modelFound->second_application_telephone.','.$modelFound->second_application_mobile_landline;
        } else {
            $owner2->phone_number = $modelFound->second_application_telephone;
        }
        $owner2->email_address = $modelFound->second_application_email_address;
        $owner2->save();

        $propertOwner = new PropertyOwner();
        $propertOwner->owner_id = $owner->id;
        $propertOwner->property_id = $propertyRecord->id;
        $propertOwner->save();

        $propertOwner2 = new PropertyOwner();
        $propertOwner2->owner_id = $owner2->id;
        $propertOwner2->property_id = $propertyRecord->id;
        $propertOwner2->save();


        /*import the images and documents*/
        $supportingDocuments = $modelFound->getSupportingDocuments()->all();
        foreach ($supportingDocuments as $currentSupportingDocuments) {
            /* @var $currentSupportingDocuments CavitySupportingDocument */

            /*if current document has type supporting_document_images */
            if ($currentSupportingDocuments->type === 'supporting_document_images') {
                /*save to property document */
                $copyFrom = Yii::getAlias('@supporting_document_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                $finalUploadName = Yii::getAlias('@upload_document_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                copy($copyFrom, $finalUploadName);

                $propertyDocument = new PropertyDocuments();
                $propertyDocument->property_id = $propertyRecord->id;
                $propertyDocument->document_name = $currentSupportingDocuments->document_name;
                $propertyDocument->save();
            } else {
                /*save it to pre appraisal images */
                $preAppraisalImage = new PropertyPreAppraisalImages();
                $copyFrom = Yii::getAlias('@supporting_document_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                $finalUploadName = Yii::getAlias('@upload_image_path') .  DIRECTORY_SEPARATOR.$currentSupportingDocuments->document_name;
                copy($copyFrom, $finalUploadName);
                $preAppraisalImage->property_id = $propertyRecord->id;
                $preAppraisalImage->image_name = $currentSupportingDocuments->document_name;
                $preAppraisalImage->save();
            }
            $questionairePropertyRecord = new QuestionairePropertyRecord();
            $questionairePropertyRecord->cavity_form_id = $modelFound->id;
            $questionairePropertyRecord->property_record_id = $propertyRecord->id;
            $questionairePropertyRecord->save();
        }
        return $this->redirect(Url::to(['/record/update', 'id' => $propertyRecord->id]));
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
