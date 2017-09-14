<?php

namespace app\models;

use app\components\LeadChangeNotifier;
use app\components\NewLeadNotifier;
use pheme\settings\components\Settings;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;

/*attach event*/
Event::on(PropertyRecord::className(), PropertyRecord::EVENT_AFTER_UPDATE, function ($event) {
    /**
     * @var $leadChangeNotifier LeadChangeNotifier
     * @var $currentModel PropertyRecord
     * @var $settings Settings
     */
    $settings = Yii::$app->settings;
    $lead_change_notify_email = $settings->get('app.lead_change_notify');
    $lead_change_notify_email = explode("\r\n", $lead_change_notify_email);

    $leadChangeNotifier = Yii::$app->leadChangeNotifier;
    $leadChangeNotifier->emailsToNotify = $lead_change_notify_email;
    $currentModel = $event->sender;
    if ($currentModel->status === $leadChangeNotifier->trigger_status) {
        $leadLink = Html::a("Click the link to open the record", Url::toRoute('/record/update/' . $currentModel->id, true));
        $leadChangeNotifier->setLeadLink( $leadLink );
        $leadChangeNotifier->sendNotification();
    }
});
Event::on(PropertyRecord::className(), PropertyRecord::EVENT_AFTER_INSERT, function ($event) {
    /**
     * @var $newLeadNotifier NewLeadNotifier
     * @var $currentModel PropertyRecord
     * @var $settings Settings
     */
    $newLeadNotifier = Yii::$app->newLeadNotifier;
    $settings = Yii::$app->settings;
    $new_lead_notify_email = $settings->get('app.new_lead_notify');
    $lead_change_notify_email = explode("\r\n", $new_lead_notify_email);
    $currentModel = $event->sender;
    $leadLink = Html::a("Click the link to view the lead", Url::toRoute('/not-submitted/' . $currentModel->id, true) );
    $newLeadNotifier->setLeadLink($leadLink);
    $newLeadNotifier->emailsToNotify = $lead_change_notify_email;
    $newLeadNotifier->sendNotification();

});

/**
 * This is the model class for table "tbl_property_record".
 *
 * @property integer $id
 * @property string $insulation_type
 * @property integer $created_by
 * @property string $postcode
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $zipcode
 * @property string $town
 * @property string $country
 * @property string $property_type
 * @property integer $number_of_bedrooms
 * @property integer $approximate_year_of_build
 * @property string $date_of_cwi
 * @property string $installer
 * @property string $product_installed
 * @property string $system_designer
 * @property string $guarantee_provider
 * @property double $guarantee_number
 * @property string $date_guarantee_issued
 * @property string $status
 * @property string $appraisal_completed
 * @property string $approximate_build
 * @property string $date_created
 * @property string $date_updated
 *
 * @property PropertyDocuments[] $propertyDocuments
 * @property PropertyImages[] $propertyImages
 * @property PropertyNotes[] $propertyNotes
 * @property PropertyOwner[] $propertyOwners
 * @property PropertyPreAppraisalImages[] $propertyPreAppraisalImages
 */
class PropertyRecord extends \yii\db\ActiveRecord
{
//    public $country = "United Kingdom";

    const PROPERTY_STATUS_NOT_SUBMITTED = 'Not Submitted';
    const PROPERTY_STATUS_PENDING_SUPERVISOR_APPROVAL = 'Pending Supervisor Approval';
    const PROPERTY_STATUS_PENDING_ADMIN_APPROVAL = 'Pending Administrator Approval';
    const PROPERTY_STATUS_PICS_BOOKED = 'Pics Booked';
    const PROPERTY_STATUS_REJECTED = 'Rejected';
    const PROPERTY_STATUS_APPROVED = 'Approved';
    const PROPERTY_STATUS_WORK_IN_PROGRESS = 'Work in Progress';
    const PROPERTY_STATUS_APPRAISAL_COMPLETE = 'Appraisal Complete';
    const PROPERTY_STATUS_APPROVED_BY_CHARTERED_SURVEYOR = 'Approved by Chartered Surveyor';
    const PROPERTY_STATUS_PASSED_TO_SOLICITOR = 'Passed to Solicitor';
    const PROPERTY_STATUS_ALL_JOBS = 'All Jobs';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_property_record';
    }

    public static function preCreateRecord()
    {
        $preCreatedRecord = new PropertyRecord();
        $preCreatedRecord->save();
        return $preCreatedRecord;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number_of_bedrooms', 'approximate_year_of_build', 'created_by'], 'integer'],
            [['date_of_cwi', 'date_guarantee_issued', 'appraisal_completed', 'date_created', 'date_updated'], 'safe'],
            [['guarantee_number'], 'number'],
            [['insulation_type', 'postcode', 'address1', 'address2', 'address3', 'zipcode', 'town', 'country', 'property_type', 'installer', 'product_installed', 'system_designer', 'guarantee_provider', 'status', 'approximate_build'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'insulation_type' => 'Insulation Type',
            'postcode' => 'Postcode',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'zipcode' => 'Zipcode',
            'town' => 'City/Town',
            'country' => 'County',
            'property_type' => 'Property Type',
            'number_of_bedrooms' => 'Number Of Bedrooms',
            'approximate_year_of_build' => 'Approximate Year Of Build',
            'date_of_cwi' => 'CWI Installation Date',
            'installer' => 'CWI Installer',
            'product_installed' => 'Product Installed',
            'system_designer' => 'System Designer',
            'guarantee_provider' => 'Guarantee Provider',
            'guarantee_number' => 'Guarantee Number',
            'date_guarantee_issued' => 'Date Guarantee Issued',
            'appraisal_completed' => 'Aprraisal Completed',
            'status' => 'Status',
            'created_by' => 'Created by',
            'approximate_build' => 'Approximate build',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyDocuments()
    {
        return $this->hasMany(PropertyDocuments::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyImages()
    {
        return $this->hasMany(PropertyImages::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyNotes()
    {
        return $this->hasMany(PropertyNotes::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyOwners()
    {
        return $this->hasMany(PropertyOwner::className(), ['property_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyPreAppraisalImages()
    {
        return $this->hasMany(PropertyPreAppraisalImages::className(), ['property_id' => 'id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => 'date_updated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
