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

class LeadChangeNotifier extends Component
{
    /**
     * @var $emailsToNotify array
     */
    public $emailsToNotify;
    /**
     * @var $trigger_status string
     */
    public $trigger_status;
    /**
     * @var $leadLink string
     */
    protected $leadLink;

    /**
     *  Sends notification to list of emails when $trigger_status is met
     */
    public function sendNotification(){
        /* @var $mailer Mailer */
        $mailer = \Yii::$app->mailer;
        $mailer->compose()
            ->setFrom('crmbuild@whitecollarclaim.co.uk')
            ->setTo($this->emailsToNotify)
            ->setSubject('Lead sent to '. PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL)
            ->setHtmlBody($this->leadLink)
            ->send();
    }

    public function setLeadLink($toRoute)
    {
        $this->leadLink = $toRoute;
    }
}