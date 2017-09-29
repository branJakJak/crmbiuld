<?php

namespace app\controllers;

use app\models\PropertyPreAppraisalImages;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class PropertyPreAppraisalImagesController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete'],
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionDelete($id)
    {
        $modelFound = $this->findModel($id);
        if ($modelFound) {
            $modelFound->delete();
            $uploadImagePath = Yii::getAlias('@upload_image_path') . DIRECTORY_SEPARATOR . $modelFound->image_name;
            unlink($uploadImagePath);
            Yii::$app->session->set('success', 'Record deleted');
        }else{
            throw new NotFoundHttpException("Pre appraisal image not found doesnt exists");
        }
        return $this->redirect(Yii::$app->request->referrer . '#w29-tab3');

    }


    /**
     * Finds the PropertyDocuments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PropertyPreAppraisalImages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyPreAppraisalImages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
