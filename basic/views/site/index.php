<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '远古神龙';
?>
<div class="site-index">

    <div class="jumbotron">
        <?php if(!Yii::$app->user->isGuest): ?>
        <p class="lead" style="color: #4cae4c; border-radius:6px;">欢迎您回来! <?= Yii::$app->user->identity->username ?> <?= Html::a(Html::img('http://bbs.womem.cn/uc_server/avatar.php?uid='.Yii::$app->user->identity->id.'&size=small'), 'http://bbs.womem.cn/home.php?mod=space&uid='.Yii::$app->user->identity->id) ?> </p>
        <?php endif; ?>
        <a class="btn btn-lg btn-success" href="http://bbs.womem.cn/forum.php">进入论坛</a>
        <a class="btn btn-lg btn-success" href="<?= Url::toRoute(['chat/index']) ?>">进入聊天室</a>
    </div>

    
   
</div>
