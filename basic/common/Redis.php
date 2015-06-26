<?php

namespace app\common;

use yii\base\Component;

class Redis extends Component {
    private $_redis;
    public $host;
    public $port;
    public $db;
    
    public function init() {
        parent::init();
        if(empty($this->_redis)) {
            $this->_redis = new \Redis();
        }
        
        $this->_redis->connect($this->host, $this->port);
        $this->_redis->select($this->db);
    }
}
