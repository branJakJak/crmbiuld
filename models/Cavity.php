<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
 * @property string $after_CWI_installation_comment
 * @property string $suffered_issues_prior_to_CWI
 * @property string $work_to_rectify_CWI
 * @property string $incured_financial_expenses
 * @property string $document_copy
 * @property string $reported_issue_to_house_insurer
 * @property string $advice_about_suitability
 * @property string $date_time_callback
 * @property string $when_property_moved
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
            [['birthday','when_property_moved', 'CWI_installation_date', 'date_created', 'date_updated'], 'safe'],
            [['CWI_payment'], 'number'],
            [['title', 'firstname', 'lastname', 'telephone_number', 'email_address', 'address1_cavity_installation', 'address2_cavity_installation', 'address3_cavity_installation', 'address_postcode_cavity_installation', 'address_town_cavity_installation', 'address_country_cavity_installation', 'CWI_installer', 'construction_type', 'property_exposure', 'after_CWI_installation_comment', 'suffered_issues_prior_to_CWI', 'work_to_rectify_CWI', 'incured_financial_expenses', 'document_copy', 'reported_issue_to_house_insurer', 'advice_about_suitability', 'date_time_callback'], 'string', 'max' => 255],
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
            'address1_cavity_installation' => 'Address1 Cavity Installation',
            'address2_cavity_installation' => 'Address2 Cavity Installation',
            'address3_cavity_installation' => 'Address3 Cavity Installation',
            'address_postcode_cavity_installation' => 'Address Postcode Cavity Installation',
            'address_town_cavity_installation' => 'Address Town Cavity Installation',
            'address_country_cavity_installation' => 'Address Country Cavity Installation',
            'CWI_installation_date' => 'CWI Installation Date',
            'CWI_installer' => 'CWI Installer',
            'construction_type' => 'Construction Type',
            'property_exposure' => 'Property Exposure',
            'CWI_payment' => 'Payment',
            'after_CWI_installation_comment' => 'After CWI Installation Comment',
            'suffered_issues_prior_to_CWI' => 'Suffered Issues Prior To  CWI',
            'work_to_rectify_CWI' => 'Work To Rectify  CWI',
            'incured_financial_expenses' => 'Incured Financial Expenses',
            'document_copy' => 'Document Copy',
            'reported_issue_to_house_insurer' => 'Reported Issue To House Insurer',
            'advice_about_suitability' => 'Advice About Suitability',
            'date_time_callback' => 'Date Time Callback',
            'when_property_moved' => 'Date when property moved',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCavitySupportingDocuments()
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
