<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_cavity_supporting_document".
 *
 * @property integer $id
 * @property integer $cavity_form_id
 * @property string $type
 * @property string $document_name
 * @property string $document_description
 * @property string $date_created
 * @property string $date_updated
 *
 * @property Cavity $cavityForm
 */
class CavitySupportingDocument extends \yii\db\ActiveRecord
{
    const FILE_TYPE_INTERAL_IMAGES = 'internal_images';
    const FILE_TYPE_PHOTO = 'photo';
    const FILE_TYPE_PROOF_OF_ADDRESS = 'proof_of_address';
    const FILE_TYPE_GUARANTOR_CERTIFICATE = 'guarantor_certificate';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cavity_supporting_document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cavity_form_id'], 'integer'],
            [['date_created', 'date_updated','document_description'], 'safe'],
            [['type', 'document_name'], 'string', 'max' => 255],
            [['cavity_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cavity::className(), 'targetAttribute' => ['cavity_form_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cavity_form_id' => 'Cavity Form ID',
            'type' => 'Type',
            'document_name' => 'Document Name',
            'document_description' => 'Document Description',
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
