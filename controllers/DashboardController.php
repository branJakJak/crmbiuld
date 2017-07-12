<?php 
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;


/**
* DashboardController
*/
class DashboardController extends Controller
{
	public $layout = "dashboard";
	public function actionIndex()
	{
		return $this->render('index');
	}	
}