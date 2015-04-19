<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '关于我';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>捐赠我</h2>

                <p><img src="<?php echo \Yii::$app->homeUrl; ?>images/playment.jpg" class="qqcode" /></p>


            </div>
            <div class="col-lg-4">
                <h2>个人微信帐号</h2>

                <p><img src="<?php echo \Yii::$app->homeUrl; ?>images/qrcode_person.jpg" class="qqcode" /></p>


            </div>
            <div class="col-lg-4">
                <h2>公众微信帐号</h2>

                <p><img src="<?php echo \Yii::$app->homeUrl; ?>images/qrcode_public.jpg" class="qqcode" /></p>
            </div>
        </div>

    </div>
