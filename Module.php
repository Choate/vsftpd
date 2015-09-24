<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choate\vsftpd;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;

/**
 * Class Module
 * @package choate\vsftpd
 * @author Choate <choate.yao@gmail.com>
 */
class Module extends \yii\base\Module
{
    public $layout = 'main';

    public $allowedIPs = ['127.0.0.1', '::1'];

    public $controllerNamespace = 'choate\vsftpd\controllers';

    public $vsftpdUserConfigPath = '/tmp';

    public $vsftpdLocalRoot = '';

    public $vsftpdUser = '';

    public $newFileMode = 0666;

    public function init() {
        parent::init();
        if (!is_writeable($this->vsftpdUserConfigPath)) {
            throw new InvalidConfigException('vsftpdUserConfigPath');
        }
        if (empty($this->vsftpdUser)) {
            throw new InvalidConfigException('vsftpdUser');
        }
    }

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (Yii::$app instanceof \yii\web\Application && !$this->checkAccess()) {
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        }

        return true;
    }


    protected function checkAccess() {
        $ip = Yii::$app->getRequest()->getUserIP();
        foreach ($this->allowedIPs as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
                return true;
            }
        }
        Yii::warning('Access to Gii is denied due to IP address restriction. The requested IP is ' . $ip, __METHOD__);

        return false;
    }
}