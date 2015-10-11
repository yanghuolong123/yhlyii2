<?php

namespace app\common;

use Yii;
use yii\web\Controller;
use app\models\Member;

class AppController extends Controller {

    public function beforeAction($action) {
        if (Yii::$app->user->isGuest && isset($_COOKIE['0r1K_'.USE_KEY.'_auth'])) {
            $auth = $_COOKIE['0r1K_'.USE_KEY.'_auth'];
            $decode = authcode($auth);
            $uid = intval(substr($decode, 33));
            if ($uid) {
                Yii::$app->user->login(Member::findUserById($uid), 3600 * 24 * 30);
            }
        }

        if (!Yii::$app->user->isGuest && !isset($_COOKIE['0r1K_'.USE_KEY.'_auth'])) {
            Yii::$app->user->logout();
        }

        return parent::beforeAction($action);
    }

}
