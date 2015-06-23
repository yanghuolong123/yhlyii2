<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class WeixinController extends Controller {

    public function actionImport($file) {
        if (!is_file($file)) {
            exit('no a file');
        }

        $listdata = [];
        $lists = file($file);
        foreach ($lists as $key => $list) {
            $data = array_map('floatval', explode("\t", $list));
            $listdata[$key]['issue'] = $data[0];
            $listdata[$key]['red'] = array_slice($data, 1, 6);
            $listdata[$key]['green'] = $data[7];
        }

        Yii::$app->mongo->selectCollection('lottery')->batchInsert($listdata);
    }

}
