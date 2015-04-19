<?php

namespace app\controllers;

use app\common\AppController;
use yii\filters\AccessControl;

class ChatController extends AppController {
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        return $this->render('index', []);
    }
    
}
