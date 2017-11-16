<?php

namespace app\controllers;

use app\components\UserHierarchyRetriever;
use yii\web\NotFoundHttpException;

class HierarchyController extends \yii\web\Controller
{
    public function actionView($id)
    {
	    throw new NotFoundHttpException();
	    
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
