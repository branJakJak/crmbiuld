<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_property_pre_appraisal_images".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $image_name
 * @property string $image_description
 * @property string $date_created
 * @property string $date_updated
 *
 * @property PropertyRecord $property
 */
class PropertyPreAppraisalImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_property_pre_appraisal_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
            [['image_name','image_description'], 'string', 'max' => 255],
            [['property_id'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'image_name' => 'Image Name',
            'image_description' => 'Image Description',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_id']);
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
