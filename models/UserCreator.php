<?php

namespace app\models;

use app\components\LeadCreatorRetriever;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use dektrium\user\models\User as User;

/**
 * This is the model class for table "tbl_user_creator".
 *
 * @property integer $id
 * @property integer $creator_id
 * @property integer $agent_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $creator
 * @property User $agent
 */
class UserCreator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_creator';
    }

    public static function isOwner($creatorId, $childId)
    {
        return UserCreator::find()->where(['creator_id' => $creatorId, 'agent_id' => $childId])->exists();
    }

    public static function isCreator($user_id)
    {
        return UserCreator::find()->where(['creator_id' => $user_id])->exists();
    }

    public static function getCreatedUsers($user_id)
    {
        return UserCreator::find()->where(['creator_id' => $user_id])->all();
    }

    /**
     * Checks whether the $subordinate_id is a subordinate of $upper_class_user_id
     * @param $upper_class_user_id
     * @param $subordinate_id
     * @return bool
     */
    public static function isSubordinate($upper_class_user_id, $subordinate_id)
    {
        /**
         * @var $leadCreatorRetriever LeadCreatorRetriever
         */
        $leadCreatorRetriever = \Yii::$app->leadCreatorRetriever;
        $leadCreatorRetriever->retrieve($upper_class_user_id);
        $userCreatedCollection = $leadCreatorRetriever->getLeadCreatorIdCollection();
        return in_array($subordinate_id, $userCreatedCollection);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator_id', 'agent_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['agent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator_id' => 'Creator ID',
            'agent_id' => 'Agent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(User::className(), ['id' => 'agent_id']);
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
