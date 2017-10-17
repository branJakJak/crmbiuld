<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_triage".
 *
 * @property integer $id
 * @property string $material_file_name
 * @property string $material_file_description
 * @property integer $property_record
 * @property string $date_created
 * @property string $date_updated
 *
 * @property PropertyRecord $propertyRecord
 */
class Triage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_triage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_record'], 'integer'],
            [['date_created', 'date_updated','material_file_description'], 'safe'],
            [['material_file_name'], 'string', 'max' => 255],
            [['property_record'], 'exist', 'skipOnError' => true, 'targetClass' => PropertyRecord::className(), 'targetAttribute' => ['property_record' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'material_file_name' => 'Material File Name',
            'material_file_description' => 'Material File Description',
            'property_record' => 'Property Record',
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyRecord()
    {
        return $this->hasOne(PropertyRecord::className(), ['id' => 'property_record']);
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
