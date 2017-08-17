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
        $pdf->SetFont("Helvetica",'',10);
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
    /**
     * @param $pdf FPDI
     * @param $tplIdx
     * @param $x_coor
     * @param $y_coor
     * @param $val_to_write
     */
    protected  function writeToPdf($pdf,$tplIdx, $x_coor, $y_coor , $val_to_write)
    {
        $pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetXY($x_coor, $y_coor);
        $pdf->Write(0, $val_to_write);
    }
    public function export(){
        /*write the information page 1*/
        $tplIdx = $this->pdf_object->importPage(1);

        $this->writeToPdf($this->pdf_object,$tplIdx , 48 , 39 +11 ,  $this->clientInformation['fullname']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 48 , 39 +19 ,  $this->clientInformation['complete_address']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 48 , 39 +36 ,  $this->clientInformation['solicitor_name']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 67 , 39 +44 ,  $this->clientInformation['solicitor_reference_number'].'test');
         $this->writeToPdf($this->pdf_object,$tplIdx , 48 , 39 +52 ,  $this->clientInformation['survey_reference']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 48 , 39 +69 ,  $this->clientInformation['triage_type']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 48 , 39 +85 ,  $this->clientInformation['report_outcome']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 12 , 39 +103 ,  $this->clientInformation['findings_of_triage_report']);
         $this->writeToPdf($this->pdf_object,$tplIdx , 12 , 39 +145 ,  $this->clientInformation['cavity_wall_insulation_case'].'teest');


        /*write the images on page 2-3*/
        $this->pdf_object->addPage();
        $tplIdx = $this->pdf_object->importPage(2);
        $image_x_coordinate = 15;
        $image_y_coordinate = 15;
        $image_width_coordinate = 50;
        $image_height_coordinate = 80;
        foreach ($this->propertyImages as $keyIndex => $currentPropertyImage) {
            $this->pdf_object->Image($currentPropertyImage, $image_x_coordinate, $image_y_coordinate, $image_width_coordinate,$image_height_coordinate);
            $this->pdf_object->Output('peek.pdf', 'I');
            if (  (($keyIndex+1) / 9) === 0 ) {
                $this->pdf_object->addPage();
                $tplIdx = $this->pdf_object->importPage(3);
                $image_x_coordinate = 15;
                $image_y_coordinate = 15;
            }
            /*resize the image and write the image floating*/
            if (  (($keyIndex+1) % 4) === 0 ) {
                /*add x */
                $image_y_coordinate += $image_height_coordinate;
                $image_x_coordinate = 0;
            }else{
                $image_x_coordinate += $image_width_coordinate;
            }
        }


    }

}