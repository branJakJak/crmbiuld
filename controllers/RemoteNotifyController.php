<?php

namespace app\controllers;

use app\models\Cavity;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class RemoteNotifyController extends \yii\web\Controller
{
    public function actionNewLead($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $cavityModel = Cavity::find()->where(['id' => $id])->one();
        if ($cavityModel) {
            $newLeadNotifier = Yii::$app->newLeadNotifier;
            $newLeadNotifier->setModel($cavityModel);
            $newLeadNotifier->sendNotification();
            return [
                'status'=>'success',
                'messsage'=>'New lead notification sent',
            ];
        }else{
            throw new NotFoundHttpException();
        }
    }
}
