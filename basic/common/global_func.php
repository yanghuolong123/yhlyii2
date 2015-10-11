<?php

//date_default_timezone_set('PRC');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('TIMESTAMP') or define('TIMESTAMP', time());
defined('DATETIME') or define('DATETIME', date('Y-m-d H:i:s', TIMESTAMP));
defined('USE_KEY') or define('USE_KEY', 'b2e7');

/**
 * 验证$_GET参数是否存在
 * 
 * @param type $params
 * @return type
 */
function validParams($params = array()) {
    $paramArr = array();
    foreach ($params as $param) {
        $paramArr[] = isset($_GET[$param]) ? $_GET[$param] : '';
    }

    return $paramArr;
}

/**
 * 日志记录
 * 
 * @param type $file
 * @param type $msg
 */
function logs($file, $msg) {
    $file = '../runtime/' . date('Y-m-d') . '_' . $file . '.log';
    error_log(date('Y-m-d H:i:s') . ' ' . $msg . "\n", 3, $file);
}

/**
 * 解析XML格式的字符串
 *
 * @param string $str
 * @return 解析正确就返回解析结果,否则返回false,说明字符串不是XML格式
 */
function xml_parser($str) {
    $xml_parser = xml_parser_create();
    if (!xml_parse($xml_parser, $str, true)) {
        xml_parser_free($xml_parser);
        return false;
    } else {
        return (json_decode(json_encode(simplexml_load_string($str)), true));
    }
}

/**
 * 数组转化为xml字符串
 * 
 * @param type $arr
 * @param type $level
 * @return string
 */
function arrToXmlStr($arr, $level = 0) {
    $xml = '';
    if ($level == 0) {
        $xml .= '<xml>' . "\n";
    }
    foreach ($arr as $key => $val) {
        $key = is_integer($key) ? 'item' : $key;
        if (!is_array($val)) {
            $xml .= is_integer($val) ? '<' . $key . '>' . $val . '</' . $key . '>' . "\n" : '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>' . "\n";
        } else {
            $xml .= '<' . $key . '>' . "\n";
            $xml .= arrToXmlStr($val, $level + 1);
            $xml .= '</' . $key . '>' . "\n";
        }
    }

    if ($level == 0) {
        $xml .= '</xml>';
    }
    return $xml;
}

/**
 * 删除目录及其下文件或文件夹
 * 
 * @param type $path
 * @param type $del_self
 */
function deleteDir($path, $del_self = false) {
    $files = glob($path . '/*');
    foreach ($files as $file_path) {
        if (is_file($file_path)) {
            unlink($file_path);
        } else {
            deleteDir($file_path);
            rmdir($file_path);
        }
    }

    if ($del_self) {
        rmdir($path);
    }
}

/**
 * http post 请求
 * 
 * @param type $url
 * @param type $postdata
 * @param type $options
 * @return type
 */
function curl_post($url = '', $postdata = '', $options = array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    if (!empty($options)) {
        curl_setopt_array($ch, $options);
    }
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

/**
 * curl get 请求
 * 
 * @param type $url
 * @param type $options
 * @return type
 */
function curl_get($url = '', $options = array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    if (!empty($options)) {
        curl_setopt_array($ch, $options);
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key != '' ? $key : md5('bb6cf8rLXvtreJbC' . (isset($_COOKIE['0r1K_'.USE_KEY.'_saltkey']) ? $_COOKIE['0r1K_'.USE_KEY.'_saltkey'] : '')));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

function formhash($specialadd = '') { 
    $authkey = md5('bb6cf8rLXvtreJbC' . (isset($_COOKIE['0r1K_'.USE_KEY.'_saltkey']) ? $_COOKIE['0r1K_'.USE_KEY.'_saltkey'] : ''));
    return substr(md5(substr(TIMESTAMP, 0, -7) . Yii::$app->user->identity->username . Yii::$app->user->identity->id . $authkey . $specialadd), 8, 8);
}
