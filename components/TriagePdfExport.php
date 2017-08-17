<?php

namespace app\components;

use FPDI;
use Yii;

class TriagePdfExport extends \yii\base\Object
{
    public $pdf_template;
    private $clientInformation = [];
    private $propertyImages;
    private $pdf_object;
    public function init()
    {
        $this->pdf_template = Yii::getAlias('@app') .DIRECTORY_SEPARATOR.'pdf_template' .DIRECTORY_SEPARATOR . 'Case_Triage_Report_Template.pdf';
        class_exists('TCPDF', true);
        $pdf = new FPDI();
        $pdf->addPage();
        $pdf->SetFont("Helvetica",'',8);
        $pdf->setSourceFile($this->pdf_template);
        $this->pdf_object = $pdf;
        return parent::init();
    }
    public function setClientInformation($clientInfo){
        $this->clientInformation = $clientInfo;
    }

    public function setPropertyImages($propertyImages){
        $this->propertyImages = $propertyImages;
    }
    public function export(){
        /*write the information page 1*/
        $tplIdx = $this->pdf_object->importPage(1);
        $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['fullname']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['complete_address']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['solicitor_name']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['solicitor_reference_number']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['survey_reference']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['triage_type']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['report_outcome']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['findings_of_triage_report']);
        // $this->writeToPdf($this->pdf_object,$tplIdx , 80 , 39 +0 ,  $this->clientInformation['cavity_wall_insulation_case']);

        /*write the images on page 2-3*/
        $tplIdx = $this->pdf_object->importPage(2);
        foreach ($this->propertyImages as $keyIndex => $currentPropertyImage) {
            if (  (($keyIndex+1) / 9) === 0 ) {
                $tplIdx = $this->pdf_object->importPage(3);
            }
            /*resize the image and write the image floating*/
            // $pdf->Image($leadObj->client_signature_image, 23, 230 +0, 100,18);
        }


    }

}