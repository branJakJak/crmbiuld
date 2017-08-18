<?php

namespace app\controllers;

use app\models\Cavity;
use app\models\CavitySupportingDocument;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\UploadedFile;

class QuestionaireController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = "front";
        $supportingDocument = new CavitySupportingDocument();
        if (Yii::$app->getRequest()->getIsAjax()) {
        	/* its an upload */
        	if($supportingDocument->load(\Yii::$app->request->post())){
        	    /*handle file upload*/
                $supportingDocument->save();
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
                    }

                }
                return $this->refresh();
            }
        	/*get cavity id from GET parameter*/
        	/*prepare supporting document */
        }


        $model = new Cavity();
        if ($model->load(\Yii::$app->request->post())) {
            /*parse date and save*/
        }
        $model->save();

        return $this->render(
            'index',
            [
                'model'=>$model,
                'supportingDocument'=>$supportingDocument,
            ]
    );
    }

}
