<?php
/* @var \yii\web\View $this */
/* @var String $content */
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

BootstrapAsset::register($this);
$menuItem = [
    ['label' => '首页', 'url' => ['index']],
    ['label' => '添加用户', 'url' => ['create']],
];
$this->beginPage();
?>
    <html>
    <head>
        <meta charset="UTF-8" />
        <?php echo Html::csrfMetaTags() ?>
        <title><?php Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php
    $this->beginBody();
    NavBar::begin(['brandLabel' => 'VSFtpd 管理', 'brandUrl' => ['user/index'], 'options' => ['class' => 'navbar-inverse']]);
    echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'items'   => [
                ['label' => 'Application', 'url' => Yii::$app->homeUrl],
            ],
        ]
    );
    NavBar::end();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="list-group">
                    <?php
                        foreach ($menuItem as $menu) {
                            echo Html::a($menu['label'], $menu['url'], ['class' => 'list-group-item'. (in_array($this->context->action->id, $menu['url']) ? ' active' : '')]);
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-9 col-sm-8">
                <?php echo $content ?>
            </div>
        </div>
    </div>
    <?php
    $this->endBody();
    ?>
    </body>
    </html>
<?php
$this->endPage();
?>