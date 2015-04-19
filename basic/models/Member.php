<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Member model
 * 
 * @property integer $uid 
 * @property string $username 
 */
class Member extends ActiveRecord implements \yii\web\IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['uid' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return NULL;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return NULL;
    }

    public static function tableName() {
        return '{{%common_member}}';
    }

    public static function findUserById($id) {
        return static::findOne(['uid' => $id]);
    }

}
