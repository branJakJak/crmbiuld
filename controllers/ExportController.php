<?php

namespace app\controllers;

use app\components\TriagePdfExport;
use app\models\Cavity;
use app\models\PropertyRecord;
use app\models\QuestionairePropertyRecord;
use yii\web\NotFoundHttpException;

class ExportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionView($id){
        /* @var $exporter TriagePdfExport */
        /* @var $questionaireProperty QuestionairePropertyRecord */
        /* @var $questionaireCavityForm Cavity */
        $propertyRecord = $this->findModel($id);
        // get the record cavity relationship record
        $questionaireProperty = $this->findQuestionairePropertyRelationshipRecord($id);
        $questionaireCavityForm = $questionaireProperty->getCavityForm()->one();

        /*prepare the text to output*/

        /*collect the images to output*/

        /*get the component that handle the exporting*/
        $exporter = \Yii::$app->triageExporter;
        $clientInformation['fullname'] = sprintf("%s %s %s",$questionaireCavityForm->title , $questionaireCavityForm->firstname,$questionaireCavityForm->lastname);
        $clientInformation['complete_address'] =
            $questionaireCavityForm->address1_cavity_installation.
            $questionaireCavityForm->address2_cavity_installation.
            $questionaireCavityForm->address3_cavity_installation.
            $questionaireCavityForm->address_postcode_cavity_installation.
            $questionaireCavityForm->address_town_cavity_installation.
            $questionaireCavityForm->address_country_cavity_installation;
        $clientInformation['solicitor_name'] = \Yii::$app->params['solicitor_name'];
        $clientInformation['solicitor_reference_number'] = '';
        $clientInformation['survey_reference'] = sprintf('CWI/%s', $propertyRecord->id);
        $clientInformation['triage_type'] = 'CWI';
        $clientInformation['report_outcome'] = 'Positive';
        $clientInformation['findings_of_triage_report'] = $prop
        $clientInformation['cavity_wall_insulation_case'] = $prop

//        $exporter->set
        /*allow user to download the file*/

        /*done*/


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
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
    /**
     * Finds the PropertyRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QuestionairePropertyRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findQuestionairePropertyRelationshipRecord($id)
    {
        $foundModel  = QuestionairePropertyRecord::find()->where(['property_record_id' => $id])->one();
        if($foundModel){
            return $foundModel;
        }else{
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
