<?php

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 9/13/2017
 * Time: 10:56 PM
 */

namespace app\components;

use app\models\PropertyRecord;
use yii\helpers\Html;
use yii\swiftmailer\Mailer;
use yii\helpers\Url;

class NewLeadNotifier {

    protected $model;
    
    public function sendNotification() {
        /* @var $mailer Mailer */
        $settings = \Yii::$app->settings;
        $new_lead_notify_email = $settings->get('app.new_lead_notify', 'app');
        $emailToNotify = explode("\r\n", $new_lead_notify_email);
        $leadLink = Html::a("Click the link to view the lead", Url::toRoute('/not-submitted/' . $this->model->id, true));
        $mailer = \Yii::$app->mailer;
        $mailer->compose()
                ->setFrom('crmbuild@whitecollarclaim.co.uk')
                ->setTo($emailToNotify)
                ->setSubject('New lead arrived')
                ->setHtmlBody($leadLink)
                ->send();
    }

    function getModel() {
        return $this->model;
    }

    function setModel(\app\models\Cavity $model) {
        $this->model = $model;
    }



}
