<?php
namespace app\modules\notifyuser\form;
use pheme\settings\components\Settings;
use Yii;

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 9/14/2017
 * Time: 2:25 AM
 */
class SettingsForm extends \yii\base\Model
{
    public $new_lead_notify;
    public $change_lead_notify;
    public $more_info;
    public $approved_by_surveyor_and_triage_complete;
    public $land_reg_checks_done_waiting_CFA_booking;
    public $cfa_complete;
    public $system_accepted;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['new_lead_notify', 'change_lead_notify'], 'required','message'=>'This field is required'],
            [['system_accepted','more_info', 'approved_by_surveyor_and_triage_complete','land_reg_checks_done_waiting_CFA_booking','cfa_complete'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'new_lead_notify' => 'New Lead Listener',
            'change_lead_notify' => 'Pending Lead Listener',
            'more_info' => 'More Information',
            'approved_by_surveyor_and_triage_complete' => 'Approved by surveyor and triage complete',
            'land_reg_checks_done_waiting_CFA_booking' => 'Land reg checks done, waiting for CFA Booking',
            'cfa_complete' => 'CFA Complete',
            'system_accepted' => 'System Accepted'
        ];
    }

    public function saveSettings()
    {
        /* @var $settings Settings */
        $settings = Yii::$app->settings;
        $settings->set('app.new_lead_notify', $this->new_lead_notify,'app','string');
        $settings->set('app.lead_change_notify', $this->change_lead_notify,'app','string');
        $settings->set('app.lead_change_notify.system_accepted', $this->system_accepted,'app','string');
        $settings->set('app.lead_change_notify.more_info', $this->more_info,'app','string');
        $settings->set('app.lead_change_notify.approved_by_surveyor_and_triage_complete', $this->approved_by_surveyor_and_triage_complete,'app','string');
        $settings->set('app.lead_change_notify.land_reg_checks_done_waiting_CFA_booking', $this->land_reg_checks_done_waiting_CFA_booking,'app','string');
        $settings->set('app.lead_change_notify.cfa_complete', $this->cfa_complete,'app','string');

    }

}