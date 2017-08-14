<?php

namespace app\rbac;

use app\models\PropertyOwner;
use app\models\PropertyRecord;
use yii\rbac\Item;
use yii\rbac\Rule;

class ConsultantEditorRule extends Rule
{
    public $name = 'isConsultant';

    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        /* @var $recordBeingAccessed PropertyRecord */
        /* @var $propertyOwner PropertyOwner */
        $recordBeingAccessed = $params['property_record'];
        $userModel = \dektrium\user\models\User::findOne(['id' => $recordBeingAccessed->created_by]);
        $isCreator = ($userModel->id === \Yii::$app->user->id);
        return $isCreator;
    }
}