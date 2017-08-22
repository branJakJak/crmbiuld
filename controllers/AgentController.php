<?php

namespace app\controllers;

use yii\filters\AccessControl;

class AgentController extends \yii\web\Controller
{
    /*admin and manager only*/
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['admin','Admin','Manager'],
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        /*show the agents that the current manager created*/
        return $this->render('index');
    }

    public function actionCreate()
    {

    }

}
