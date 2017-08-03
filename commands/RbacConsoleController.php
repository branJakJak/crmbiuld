<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/25/2017
 * Time: 11:06 PM
 */

namespace app\commands;


use Yii;
use yii\console\Controller;
use yii\db\Exception;
use yii\rbac\PhpManager;

class RbacConsoleController extends Controller
{
    public function actionInit(){
        /**
         * @var $authManager PhpManager
         */
        $authManager = \Yii::$app->authManager;
        $admin = $authManager->createRole('admin');
        $agent = $authManager->createRole('agent');
        $authManager->add($admin);
        $authManager->add($agent);
        try {
            $authManager->assign($admin, 1);
        } catch (\Exception $e) {

        }
    }
    public function actionAddNewRole($role){
        $authManager = Yii::$app->authManager;
        $currentRoleObj = $authManager->createRole($role);
        $authManager->add($currentRoleObj);
    }
    public function actionInitManagerRole() {
        /**
         * @var $recordCreated \dektrium\user\models\User
         * @var $authManager \yii\rbac\PhpManager
         */
        /*get role and assign permission*/
        try{
            $authManager = Yii::$app->authManager;
            $editOwnRecordRule = new \app\rbac\ManagerEditorRule();
            $authManager->add($editOwnRecordRule);
            $editOwnRecordPermission = $authManager->createPermission("managerPermission");
            $editOwnRecordPermission->description = "manager permission";
            $editOwnRecordPermission->ruleName = $editOwnRecordRule->name;
            $authManager->add($editOwnRecordPermission);


            $managerRole = $authManager->getRole('Manager');
            $authManager->addChild($managerRole, $editOwnRecordPermission);
            echo "Manager role rule initialization succeeded";
        }catch (Exception $ex){
            echo "An error occured while executing initialization." . $ex->getMessage();
        }

    }
    public function actionInitConsultantRole(){
        /**
         * @var $recordCreated \dektrium\user\models\User
         * @var $authManager \yii\rbac\PhpManager
         */
        /*get role and assign permission*/
        try{
            $authManager = Yii::$app->authManager;
            $consultantEditordRule = new \app\rbac\ConsultantEditorRule();
            $authManager->add($consultantEditordRule);
            $consultantPermission = $authManager->createPermission("editOwnRecordPermission");
            $consultantPermission->description = "edit record owned by this";
            $consultantPermission->ruleName = $consultantEditordRule->name;
            $authManager->add($consultantPermission);

            $consultantRole = $authManager->getRole('Consultant');
            $authManager->addChild($consultantRole, $consultantPermission);
            echo "Consultant role rule initialization succeeded";
        }catch (Exception $ex){
            echo "An error occured while executing initialization." . $ex->getMessage();
        }


    }

}