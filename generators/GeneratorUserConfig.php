<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choate\vsftpd\generators;

use yii\base\Component;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
 * Class GeneratorUserConfig
 * @package choate\vsftpd\generators
 * @author Choate <choate.yao@gmail.com>
 */
class GeneratorUserConfig extends Component
{
    /**
     * @var \choate\vsftpd\Module
     * @author Choate <choate.yao@gmail.com>
     */
    protected $_module;

    /**
     * @var \choate\vsftpd\models\User
     * @author Choate <choate.yao@gmail.com>
     */
    protected $_model;

    /**
     *
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     * @return bool|int
     */
    function generate() {
        $path = $this->getModule()->vsftpdUserConfigPath;
        if (!is_writable($path)) {
            return false;
        }
        $model = $this->getModel();

        $result = file_put_contents("{$path}/{$model->username}", $model->buildConfigBySetting());
        chown("{$path}/{$model->username}", $this->getModule()->vsftpdUser);
    }

    /**
     *
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     * @return bool
     */
    public function remove() {
        $model = $this->getModel();
        $path  = $this->getModule()->vsftpdUserConfigPath . "/{$model->username}";
        if (!is_writable($path)) {
            return false;
        }

        return unlink($path);
    }

    /**
     * Module
     * @author Choate <choate.yao@gmail.com>
     * @return \choate\vsftpd\Module
     */
    public function getModule() {
        return $this->_module;
    }

    /**
     * @param \choate\vsftpd\Module $module
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setModule($module) {
        $this->_module = $module;
    }

    /**
     * Model
     * @author Choate <choate.yao@gmail.com>
     * @return \choate\vsftpd\models\User
     */
    public function getModel() {
        return $this->_model;
    }

    /**
     * @param \choate\vsftpd\models\User $model
     *
     * @author Choate <choate.yao@gmail.com>
     */
    public function setModel($model) {
        $this->_model = $model;
    }

}