<?php

namespace app\common;

use yii\base\Component;

class Mongo extends Component {

    public $host;
    public $port;
    public $db;
    private $_mongo;

    public function init() {
        parent::init();
        $this->_mongo = new \MongoClient("mongodb://" . $this->host . ':' . $this->port);
    }

    public function getDB() {
        return $this->_mongo->selectDB($this->db);
    }

    public function selectCollection($collname) {
        return $this->getDB()->selectCollection($collname);
    }

}
