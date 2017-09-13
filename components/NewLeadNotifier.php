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

class NewLeadNotifier
{
    /**
     * @var $emailsToNotify array
     */
    public $emailsToNotify;

    protected $leadLink;

    public function sendNotification(){
        /* @var $mailer Mailer */
        $mailer = \Yii::$app->mailer;
        $mailer->compose()
            ->setFrom('crmbuild@whitecollarclaim.co.uk')
            ->setTo($this->emailsToNotify)
            ->setSubject('New lead arrived')
            ->setHtmlBody($this->leadLink)
            ->send();

    }

    public function setLeadLink($leadLink)
    {
        $this->leadLink = $leadLink;
    }
}