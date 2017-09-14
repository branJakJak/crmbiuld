<?php

namespace app\modules\notifyuser\controllers;

use app\modules\notifyuser\form\SettingsForm;
use Yii;
use yii\filters\AccessControl;
use yii\validators\EmailValidator;
use yii\web\Controller;

/**
 * Default controller for the `notifyuser` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new SettingsForm();
        $settings = Yii::$app->settings;
        $lead_change_notify_email = $settings->get('app.lead_change_notify');
        $new_lead_notify_email = $settings->get('app.new_lead_notify');
        $model->change_lead_notify = $lead_change_notify_email;
        $model->new_lead_notify = $new_lead_notify_email;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $emailValidator = new EmailValidator();
            $newLeadEmailsArr = explode("\r\n", $model->new_lead_notify);
            $newLeadEmailsArr = array_filter($newLeadEmailsArr);
            foreach ($newLeadEmailsArr as $currentEmail) {
                if (!$emailValidator->validate($currentEmail)) {
                    $model->addError('new_lead_notify', 'There is an email that is not valid . ');
                }
            }
            $changeLeadEmailsArr = explode("\r\n", $model->change_lead_notify);
            $changeLeadEmailsArr = array_filter($changeLeadEmailsArr);
            foreach ($changeLeadEmailsArr as $currentEmail) {
                if (!$emailValidator->validate($currentEmail)) {
                    $model->addError('change_lead_notify', 'There is an email that is not valid . ');
                }
            }
            if (!$model->hasErrors()) {
                $model->new_lead_notify = implode("\r\n", $newLeadEmailsArr);
                $model->change_lead_notify = implode("\r\n", $changeLeadEmailsArr);
                $model->saveSettings();
                \Yii::$app->session->set("success", "Record saved");
            }

        }
        return $this->render('index',[
            'model'=>$model
        ]);
    }
}
