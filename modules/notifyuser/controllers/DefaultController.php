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
class DefaultController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'Admin'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $model = new SettingsForm();
        $settings = Yii::$app->settings;
        
        $new_lead_notify_email = $settings->get('app.new_lead_notify', 'app');
        $lead_change_notify_email = $settings->get('app.lead_change_notify', 'app');// Pending Surveyors Approval
        $more_inf = $settings->get('app.lead_change_notify.more_info', 'app');
        $approved_by_surveyor_and_triage_complet = $settings->get('app.lead_change_notify.approved_by_surveyor_and_triage_complete', 'app');
        $land_reg_checks_done_waiting_CFA_bookin = $settings->get('app.lead_change_notify.land_reg_checks_done_waiting_CFA_booking', 'app');
        $cfa_complet = $settings->get('app.lead_change_notify.cfa_complete', 'app');
        $system_accepted = $settings->get('app.lead_change_notify.system_accepted', 'app');

        $model->change_lead_notify = $lead_change_notify_email;
        $model->new_lead_notify = $new_lead_notify_email;
        $model->more_info = $more_inf;
        $model->approved_by_surveyor_and_triage_complete = $approved_by_surveyor_and_triage_complet;
        $model->land_reg_checks_done_waiting_CFA_booking = $land_reg_checks_done_waiting_CFA_bookin;
        $model->cfa_complete = $cfa_complet;
        $model->system_accepted = $system_accepted;


        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            // @TODO - validate email fields
            $model = $this->validateEmailFields($model);
            if (!$model->hasErrors()) {
                $model->saveSettings();
                Yii::$app->session->addFlash("success", "Emails saved");
                return $this->refresh();
            }
        }
        return $this->render('index', [
                    'model' => $model
        ]);
    }

    protected function validateEmailFields(SettingsForm $model) {
        $emailValidator = new EmailValidator();
        foreach ($model->attributes as $currentAttribute => $currentVal) {
            $newLeadEmailsArr = explode("\r\n", $model->$currentAttribute);
            $newLeadEmailsArr = array_filter($newLeadEmailsArr);
            foreach ($newLeadEmailsArr as $currentEmail) {
                if (!$emailValidator->validate($currentEmail)) {
                    $model->addError($currentAttribute, 'There is an email that is not valid . ');
                    return $model;
                }
            }
            $model->$currentAttribute = implode("\r\n", $newLeadEmailsArr);
        }
        return $model;
    }

}
