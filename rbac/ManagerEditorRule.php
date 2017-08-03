<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 8/3/2017
 * Time: 9:31 PM
 */

namespace app\rbac;


use app\models\PropertyOwner;
use app\models\PropertyRecord;
use yii\helpers\VarDumper;
use yii\rbac\Item;
use yii\rbac\Role;
use yii\rbac\Rule;

class ManagerEditorRule extends Rule
{

    public $name = 'isManager';
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
        /* @var $currentRole Role*/
        $recordBeingAccessed = $params['property_record'];
        $userModel = \dektrium\user\models\User::findOne(['id' => $recordBeingAccessed->created_by]);
        $isOwner = false;
        $recordOwnedByConsultant = false;
        if($userModel){
            $user = intval($user);
            $isOwner = ($userModel->id === \Yii::$app->user->id);
            /* check if record is owned by consultant */
            $rolesOfOwner = \Yii::$app->authManager->getRolesByUser($userModel->id);
            foreach ($rolesOfOwner as $currentRole) {
                if ($currentRole->name === 'Consultant' ) {
                    $recordOwnedByConsultant = true;
                    break;
                }
            }
//            \Yii::info(VarDumper::dumpAsString('Current logged in user is '. \Yii::$app->user->id));
//            \Yii::info(VarDumper::dumpAsString('Owner is '.$userModel->id));
//            \Yii::info(VarDumper::dumpAsString('Is owner of the record '));
//            \Yii::info(VarDumper::dumpAsString($isOwner));
//            \Yii::info(VarDumper::dumpAsString('Is not owner consultant '));
//            \Yii::info(VarDumper::dumpAsString(!$recordOwnedByConsultant ));
//            \Yii::info(VarDumper::dumpAsString('Is valid '));
//            \Yii::info(VarDumper::dumpAsString($isOwner && !$recordOwnedByConsultant));
        }
        return $isOwner && !$recordOwnedByConsultant;
    }
}