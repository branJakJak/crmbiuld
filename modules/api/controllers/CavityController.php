<?php

namespace app\modules\api\controllers;

use app\models\Cavity;
use app\models\CavitySupportingDocument;
use Yii;
use yii\db\Exception;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CavityController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $cavityForm = new Cavity();
        if ($cavityForm->load(\Yii::$app->getRequest()->post())) {

            if ($cavityForm->save()) {
                return Json::encode([
                    'id' => $cavityForm->id
                ]);
            } else {
                return Json::encode($cavityForm->getErrors());
            }
        } else {
            throw new Exception('Incomplete parameters');
        }
    }

    public function actionUpload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $supportingDocument = new CavitySupportingDocument();
        if (isset($_POST['supportingDocument']) && !empty($_POST['supportingDocument'])) {
            $originalFileName = $_POST['supportingDocumentOriginalFileName'];
            $supportingDocument->type = $_POST['supportingDocumentType'];
            $supportingDocument->cavity_form_id = intval($_POST['cavity_form_id']);
            /* save the file to supporting documents*/
            $supportingDocument->document_name = uniqid() . '-' . $originalFileName;
            $finalUploadName = Yii::getAlias('@supporting_document_path') . DIRECTORY_SEPARATOR . $supportingDocument->document_name;
            $downloadCommand = sprintf("wget -O %s %s", $finalUploadName, $_POST['supportingDocument']);
            shell_exec($downloadCommand);
            if ($supportingDocument->save()) {
                return Json::encode([
                    'id' => $supportingDocument->id
                ]);
            } else {
                return Json::encode($supportingDocument->getErrors());
            }
        } else {
            throw new Exception('Incomplete parameters');
        }

    }
}