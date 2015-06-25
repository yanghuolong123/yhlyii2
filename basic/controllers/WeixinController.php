<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class WeixinController extends Controller {

    public $token = 'yanghuolonghebingbing123';
    public $appid = 'wxa2d2cc7d8b460302';
    public $secret = 'f36da1173c0a891fdac60da21cbb7c06';
    public $api_url = 'https://api.weixin.qq.com';
    private $_accessToken;

    public function actionIndex() {
        list($echostr) = validParams(array('echostr'));

        if ($this->checkSignature()) {
            if (empty($echostr)) {
                $this->responseMsg();
            } else {
                echo $echostr;
            }
        } else {
            logs('wx', 'valid error: ' . var_export(isset($_GET) ? $_GET : 'unknown', TRUE));
        }

        exit(0);
    }

    public function responseMsg() {
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"]) ? $GLOBALS["HTTP_RAW_POST_DATA"] : '';
        if (empty($postStr)) {
            logs('wx', 'postStr empty!');
            exit(1);
        }
        logs('wx', 'postStr:' . var_export($postStr, true));

        libxml_disable_entity_loader(true);
        $postStr = preg_replace('/<!\[CDATA\[(.*)\]\]>/', '$1', $postStr);
        $data = xml_parser($postStr);
        // logs('wx', 'data:' . var_export($data, true));

        $this->listen($data);

        $msgArr['ToUserName'] = $data['FromUserName'];
        $msgArr['FromUserName'] = $data['ToUserName'];
        $msgArr['CreateTime'] = time();
        $msgArr['MsgType'] = 'text';
        $msgArr['Content'] = '亲，远古神龙欢迎您的到来！' . " \n 1、输入\"双色球\"，可以推荐给你幸运号码";
        $this->sendMsg($msgArr);
    }

    public function listen($data) {
        $this->subscribe($data);
        $this->lottery($data);
    }

    // 彩票
    public function lottery(&$data) {
        if (isset($data['Content']) && ($data['Content'] != '双色球')) {
            $green = $red = [];
            $listsData = iterator_to_array(Yii::$app->mongo->selectCollection('lottery')->find()->sort(array('issue' => -1)), false);
            foreach ($listsData as &$list) {
                unset($list['_id']);
                $green[$list['green']] ++;
                foreach ($list['red'] as $r) {
                    $red[$r] ++;
                }
            }
            rsort($red);

            $msgArr['ToUserName'] = $data['FromUserName'];
            $msgArr['FromUserName'] = $data['ToUserName'];
            $msgArr['CreateTime'] = time();
            $msgArr['MsgType'] = 'text';
            $msgArr['Content'] = implode(', ', array_slice($red, 0, 6)) . '   ' . max($green);
            $this->sendMsg($msgArr);
        }
    }

    // 定阅
    public function subscribe(&$data) {
        if (isset($data['Event']) && $data['Event'] == 'subscribe') {
            $msgArr['ToUserName'] = $data['FromUserName'];
            $msgArr['FromUserName'] = $data['ToUserName'];
            $msgArr['CreateTime'] = time();
            $msgArr['MsgType'] = 'text';
            $msgArr['Content'] = '亲，感谢你的支持！' . " \n 1、输入\"双色球\"，可以推荐给你幸运号码";
            $this->sendMsg($msgArr);
        }
    }

    private function checkSignature() {
        list($signature, $timestamp, $nonce) = validParams(array('signature', 'timestamp', 'nonce'));

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function sendMsg($arr) {
        echo arrToXmlStr($arr);
        exit(0);
    }

    public function getAccessToken() {
        $cache = Yii::$app->cache;
        $this->_accessToken = $cache->get('access_token_' . $this->appid);
        if (!empty($this->_accessToken)) {
            return $this->_accessToken;
        }

        $url = $this->api_url . '/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->secret;
        $data = json_decode(curl_get($url), true);

        if (isset($data['access_token']) && isset($data['expires_in'])) {
            $this->_accessToken = $data['access_token'];
            $cache->set('access_token_' . $this->appid, $data['access_token'], $data['expires_in'] - 3600);
        }

        return $this->_accessToken;
    }

    protected function urlencodeArr($arr) {
        foreach ($arr as $key => $val) {
            if (is_scalar($val)) {
                $arr[$key] = urlencode($val);
            }
            if (is_array($val)) {
                $arr[$key] = $this->urlencodeArr($val);
            }
        }

        return $arr;
    }

    public function actionCreateMenu() {
        $url = $this->api_url . '/cgi-bin/menu/create?access_token=' . $this->accessToken;
        $menu = json_decode(file_get_contents(Yii::$app->basePath . '/doc/weixin-menu.txt'), true);
        $menu = $this->urlencodeArr($menu);
        $data = curl_post($url, urldecode(json_encode($menu)));
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }

}
