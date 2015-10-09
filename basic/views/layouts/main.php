<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => '<img style="height:42px;" src="http://bbs.womem.cn/static/image/yhl/womem_logo.gif" />',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => '首页', 'url' => ['/site/index']],
                    ['label' => '关于我', 'url' => ['/site/about']],
                    ['label' => '联系我', 'url' => ['/site/contact']],
                    Yii::$app->user->isGuest ?
                        ['label' => '登录', 'url' => 'http://bbs.womem.cn/member.php?mod=logging&action=login'] :
                        ['label' => '退出 (' . Yii::$app->user->identity->username . ')',
                            'url' => 'http://bbs.womem.cn/member.php?mod=logging&action=logout&formhash='.  formhash(),
                            'linkOptions' => ['data-method' => 'post']],
                    Yii::$app->user->isGuest ? ['label'=>'注册', 'url'=>'http://bbs.womem.cn/member.php?mod=register'] : '',
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label'=>'首页', 'url' => ['/site/index']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; 远古神龙 <?= date('Y') ?> (<a target="_blank" href="http://www.miitbeian.gov.cn/">京ICP备15051718号</a>)</p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
