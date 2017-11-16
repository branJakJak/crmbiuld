<?php

namespace app\controllers;

use app\components\UserHierarchyRetriever;

class HierarchyController extends \yii\web\Controller
{
    public function actionView($id)
    {
	    $id = intval( $id );
	    $userHierarchy = [];
	    $userHierarchyObj = new UserHierarchyRetriever( );
	    $userHierarchy = $userHierarchyObj->retrieve($id);
	    $userHierarchy = array_reverse( $userHierarchy );
	    $userCreatorArr = array_shift( $userHierarchy );
	    $userCreator = $userCreatorArr['name'];


        return $this->render('view',[
        	'userHierarchy'=>$userHierarchy,
        	'userCreator'=>$userCreator,
        ]);
    }

}
