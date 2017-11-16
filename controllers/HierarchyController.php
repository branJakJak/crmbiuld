<?php

namespace app\controllers;

use app\components\UserHierarchyRetriever;

use dektrium\user\models\User;
use Tree\Node\Node;
use yii\web\NotFoundHttpException;

class HierarchyController extends \yii\web\Controller
{
    public function actionView($id)
    {
	    $id = intval( $id );
	    $userHierarchy = [];
	    $userHierarchyObj = new UserHierarchyRetriever( );
	    /*get the name of the user and role*/
	    $user = User::findOne( $id );
	    /*pass the parent node*/
	    $parentNode = new Node();
	    $userHierarchyObj->retrieve($id,$parentNode);
        return $this->render('view',[
        	'parentNode'=>$parentNode,
        ]);
    }

}
