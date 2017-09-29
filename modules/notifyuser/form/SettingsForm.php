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

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['new_lead_notify', 'change_lead_notify'], 'required','message'=>'This field is required'],
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
        ];
    }

    public function saveSettings()
    {
        /* @var $settings Settings */
        $settings = Yii::$app->settings;
        $settings->set('app.new_lead_notify', $this->new_lead_notify);
        $settings->set('app.lead_change_notify', $this->change_lead_notify);
    }

}