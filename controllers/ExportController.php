<?php

namespace app\controllers;

use app\components\TriagePdfExport;
use app\models\Cavity;
use app\models\PropertyNotes;
use app\models\PropertyRecord;
use app\models\QuestionairePropertyRecord;
use app\models\Triage;
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
        /*get property note that has type */
        $triageNotesQuery = PropertyNotes::find()
            ->where(['property_id' => $propertyRecord->id ,'note_type'=>PropertyNotes::NOTE_TYPE_TRIAGE])
            ->orderBy(['date_created' => SORT_DESC]);
        $triageNoteObjects = $triageNotesQuery->all();// get the latest note that has type triage_note
        $triageNoteText = '';
        if($triageNotesQuery->exists()){
            foreach ($triageNoteObjects as $currentTriageNoteObject) {
                $triageNoteText .= $currentTriageNoteObject->content.".";
            }
        }

        /*prepare the text to output*/

        /*collect the images to output*/
        $propertyImageCollection = [];
        /*get all triage that has property id */

        $triageDocuments = Triage::find()->where(['property_record' => $propertyRecord->id])->all();
        foreach ($triageDocuments as $currentTriageDocument) {
            /* @var $currentTriageDocument Triage */
            $triagePath = \Yii::getAlias('@triage_path');
            $propertyImageCollection[] = $triagePath.DIRECTORY_SEPARATOR.$currentTriageDocument->material_file_name;
        }

        /*get the component that handle the exporting*/
        $exporter = \Yii::$app->triageExporter;
        $clientInformation['fullname'] = sprintf("%s %s %s",$questionaireCavityForm->title , $questionaireCavityForm->firstname,$questionaireCavityForm->lastname);
        $clientInformation['complete_address'] = [
                $questionaireCavityForm->address1_cavity_installation,
                $questionaireCavityForm->address2_cavity_installation,
                $questionaireCavityForm->address3_cavity_installation,
                $questionaireCavityForm->address_postcode_cavity_installation,
                $questionaireCavityForm->address_town_cavity_installation,
                $questionaireCavityForm->address_country_cavity_installation
        ];
        $clientInformation['complete_address'] = implode(" ", $clientInformation['complete_address']);
        $clientInformation['solicitor_name'] = \Yii::$app->params['solicitor_name'];
        $clientInformation['solicitor_reference_number'] = '';
        $clientInformation['survey_reference'] = sprintf('CWI/%s', $propertyRecord->id);
        $clientInformation['triage_type'] = 'CWI';
        $clientInformation['report_outcome'] = 'Positive';
        $clientInformation['findings_of_triage_report'] = $triageNoteText;
        $clientInformation['cavity_wall_insulation_case'] = $propertyRecord->insulation_type;
        $exporter->setClientInformation($clientInformation);
        $exporter->setPropertyImages($propertyImageCollection);
        /*allow user to download the file*/
        $exporter->export();
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
