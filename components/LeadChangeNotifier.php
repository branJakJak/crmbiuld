<?php

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 9/13/2017
 * Time: 10:45 PM
 */

namespace app\components;

use app\models\PropertyRecord;
use yii\base\Component;
use yii\swiftmailer\Mailer;
use yii\helpers\Html;
use yii\helpers\Url;

class LeadChangeNotifier extends Component
{

    /**
     * @var $config array
     */
    public $config;

    /**
     * @var PropertyRecord $model
     */
    public $model;

    /**
     *  Sends notification to list of emails when $trigger_status is met
     */
    public function sendNotification()
    {
        foreach ($this->config as $key => $val) {
            if ($this->model->status == $key) {
                //get the emails to notify
                $settings = \Yii::$app->settings;
                $emailsToNotify = $settings->get($val, 'app');
                $emailsToNotify = explode("\r\n", $emailsToNotify);
                $emailsToNotify = array_filter($emailsToNotify);//remove empty
                $leadLink = Html::a("Click the link to open the record", Url::toRoute('/record/update/' . $this->model->id, true));
                /* @var $mailer Mailer */
                $mailer = \Yii::$app->mailer;
                if (!empty($emailsToNotify)) {
                    $mailer->compose()
                        ->setFrom('crmbuild@whitecollarclaim.co.uk')
                        ->setTo($emailsToNotify)
                        ->setSubject('Lead sent to ' . $key)
                        ->setHtmlBody($leadLink)
                        ->send();
                }

            }
        }
    }
}
