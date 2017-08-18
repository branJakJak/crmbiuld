<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_property_notes".
 *
 * @property integer $id
 * @property integer $property_id
 * @property string $content
 * @property string $note_type
 * @property integer $created_by
 * @property string $date_created
 * @property string $date_updated
 *
 * @property PropertyRecord $property
 * @property PropertyRecord $creator
 */
class PropertyNotes extends \yii\db\ActiveRecord
{
    const NOTE_TYPE_INFO = 'info_type';
    const NOTE_TYPE_TRIAGE = 'triage_type';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_property_notes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'created_by'], 'integer'],
            [['content','note_type'], 'string'],
            [['date_created', 'date_updated'], 'safe'],
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
            'content' => 'Content',
            'note_type' => 'Note Type',
            'created_by' => 'Created By',
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

    public function getCreator()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'created_by']);
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
