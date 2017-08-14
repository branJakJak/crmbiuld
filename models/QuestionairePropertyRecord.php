<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_questionaire_property_record".
 *
 * @property integer $id
 * @property integer $property_record_id
 * @property integer $cavity_form_id
 * @property string $date_created
 * @property string $date_updated
 *
 * @property Cavity $cavityForm
 * @property PropertyRecord $propertyRecord
 */
class QuestionairePropertyRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_questionaire_property_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_record_id', 'cavity_form_id'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
            [['cavity_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cavity::className(), 'targetAttribute' => ['cavity_form_id' => 'id']],
            [['property_record_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_record_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_record_id' => 'Property Record ID',
            'cavity_form_id' => 'Cavity Form ID',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCavityForm()
    {
        return $this->hasOne(Cavity::className(), ['id' => 'cavity_form_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyRecord()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_record_id']);
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
