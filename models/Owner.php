<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_owner".
 *
 * @property integer $id
 * @property string $title
 * @property string $firstname
 * @property string $lastname
 * @property string $company_name
 * @property string $email_address
 * @property string $mobile_number
 * @property string $phone_number
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $postalcode
 * @property string $town
 * @property string $country
 * @property string $date_created
 * @property string $date_updated
 */
class Owner extends \yii\db\ActiveRecord
{
    public $country = "United Kingdom";
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_owner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_created', 'date_updated'], 'safe'],
            [[ 'email_address'], 'email'],
            [['title', 'firstname', 'lastname', 'company_name', 'email_address', 'mobile_number', 'phone_number', 'address1', 'address2', 'address3', 'postalcode', 'town', 'country'], 'string', 'max' => 255],
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
            'company_name' => 'Company Name',
            'email_address' => 'Email Address',
            'mobile_number' => 'Mobile Number',
            'phone_number' => 'Phone Number',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'postalcode' => 'Postalcode',
            'town' => 'City/Town',
            'country' => 'Country',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    public function beforeDelete()
    {
        /*delete the property owner record */
        PropertyOwner::deleteAll(['owner_id' => $this->id]);
        return parent::beforeDelete();
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
