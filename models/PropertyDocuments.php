<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_property_documents".
 *
 * @property integer $id
 * @property integer $property_id
 * @property integer $uploaded_by
 * @property string $document_name
 * @property string $date_created
 * @property string $date_updated
 *
 * @property PropertyRecord $property
 * @property \dektrium\user\models\User $uploader
 */
class PropertyDocuments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_property_documents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id','uploaded_by'], 'integer'],
            [['date_created', 'date_updated'], 'safe'],
            [['document_name'], 'string', 'max' => 255],
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
            'uploaded_by' => 'Uploaded by',
            'document_name' => 'Document Name',
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

    public function getUploader()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'uploaded_by']);
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