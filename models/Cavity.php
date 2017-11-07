<?php

namespace app\models;

use app\components\NewLeadNotifier;
use pheme\settings\components\Settings;
use Yii;
use yii\base\Event;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;

Event::on(Cavity::className(), Cavity::EVENT_AFTER_INSERT, function ($event) {
    /* @var $newLeadNotifier NewLeadNotifier */
    $newLeadNotifier = Yii::$app->newLeadNotifier;
    $newLeadNotifier->setModel($event->sender);
    $newLeadNotifier->sendNotification();
});

/**
 * This is the model class for table "tbl_cavity".
 *
 * @property integer $id
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property string $telephone_number
 * @property string $email_address
 * @property string $address1_cavity_installation
 * @property string $address2_cavity_installation
 * @property string $address3_cavity_installation
 * @property string $address_postcode_cavity_installation
 * @property string $address_town_cavity_installation
 * @property string $address_country_cavity_installation
 * @property string $CWI_installation_date
 * @property string $CWI_installer
 * @property string $construction_type
 * @property string $property_exposure
 * @property double $CWI_payment
 * @property string $after_CWI_installation_date
 * @property string $suffered_issues_prior_to_CWI
 * @property string $work_to_rectify_CWI
 * @property string $incured_financial_expenses
 * @property string $document_copy
 * @property string $reported_issue_to_house_insurer
 * @property string $advice_about_suitability
 * @property string $date_time_callback
 * @property string $when_property_moved
 * @property string $is_in_IVA_or_Bankrupt
 * @property string $created_by_user
 * @property string $mobile_landline
 * @property string second_application_title
 * @property string second_application_firstname
 * @property string second_application_lastname
 * @property string second_application_birthday
 * @property string second_application_telephone
 * @property string second_application_mobile_landline
 * @property string second_application_email_address
 * @property string property_history
 * @property string further_notes
 * @property string $date_created
 * @property string $date_updated
 *
 * @property CavitySupportingDocument[] $tblCavitySupportingDocuments
 */
class Cavity extends \yii\db\ActiveRecord
{
    public $address_country_cavity_installation = 'United Kingdom';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cavity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'further_notes' , 'birthday','when_property_moved', 'CWI_installation_date','property_history' ,'date_created', 'date_updated'], 'safe'],
//            [['telephone_number', 'address_postcode_cavity_installation'], 'unique', 'targetAttribute' => ['telephone_number', 'address_postcode_cavity_installation']],
            [['CWI_payment'], 'number'],
            [['email_address'], 'email'],
            [['title', 'firstname', 'lastname','address1_cavity_installation', 'address2_cavity_installation', 'address3_cavity_installation', 'address_postcode_cavity_installation', 'address_town_cavity_installation', 'address_country_cavity_installation', 'CWI_installer', 'construction_type', 'property_exposure', 'after_CWI_installation_date', 'suffered_issues_prior_to_CWI', 'work_to_rectify_CWI', 'incured_financial_expenses', 'document_copy', 'reported_issue_to_house_insurer', 'advice_about_suitability', 'date_time_callback','is_in_IVA_or_Bankrupt','created_by_user','mobile_landline','second_application_title','second_application_firstname','second_application_lastname','second_application_birthday','second_application_telephone','second_application_mobile_landline','second_application_email_address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'birthday' => 'Birthday',
            'telephone_number' => 'Telephone Number',
            'email_address' => 'Email Address',
            'address1_cavity_installation' => 'Address1',
            'address2_cavity_installation' => 'Address2',
            'address3_cavity_installation' => 'Address3',
            'address_postcode_cavity_installation' => 'Postcode',
            'address_town_cavity_installation' => 'Town',
            'address_country_cavity_installation' => 'Country',
            'CWI_installation_date' => 'CWI Installation Date',
            'CWI_installer' => 'CWI Installer',
            'construction_type' => 'Construction Type',
            'property_exposure' => 'Property Exposure',
            'CWI_payment' => 'Payment',
            'after_CWI_installation_date' => 'After CWI Installation Date',
            'suffered_issues_prior_to_CWI' => 'Suffered Issues Prior To  CWI',
            'work_to_rectify_CWI' => 'Work To Rectify  CWI',
            'incured_financial_expenses' => 'Incured Financial Expenses',
            'document_copy' => 'Document Copy',
            'reported_issue_to_house_insurer' => 'Reported Issue To House Insurer',
            'advice_about_suitability' => 'Advice About Suitability',
            'date_time_callback' => 'Date Time Callback',
            'when_property_moved' => 'Date when property moved',
            'is_in_IVA_or_Bankrupt' => 'Is in IVA or Bankrupt',
            'created_by_user' => 'Created by user',
            'mobile_landline' => 'Other contact number',
            'second_application_title' => 'Title (second applicant)',
            'second_application_firstname' => 'Firstname (second applicant)',
            'second_application_lastname' => 'Lastname (second applicant)',
            'second_application_birthday' => 'Birthday (second applicant)',
            'second_application_telephone' => 'Telephone (second applicant)',
            'second_application_mobile_landline' => 'Other contact number (second applicant)',
            'second_application_email_address' => 'Email Address (second applicant)',
            'property_history' => 'Property History',
            'further_notes' => 'Further Notes',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupportingDocuments()
    {
        return $this->hasMany(CavitySupportingDocument::className(), ['cavity_form_id' => 'id']);
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
