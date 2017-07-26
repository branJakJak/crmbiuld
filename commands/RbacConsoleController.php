<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/25/2017
 * Time: 11:06 PM
 */

namespace app\commands;


use yii\console\Controller;
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

}