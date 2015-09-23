<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choate\vsftpd\models;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class User
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $setting
 * @package choate\vsftpd\models
 * @author Choate <choate.yao@gmail.com>
 */
class User extends ActiveRecord
{
    public $downloadEnable;

    public $writeEnable;

    public $cmdsDenied;

    public $localRoot;

    public static function tableName() {
        return '{{vsftpd_user}}';
    }

    public function rules() {
        return [
            [['username', 'password', 'downloadEnable', 'writeEnable', 'localRoot'], 'required'],
            ['username', 'unique'],
            ['username', 'match', 'pattern' => '#^[a-z][a-z0-9]+$#'],
            ['password', 'string', 'min' => 6, 'max' => 45],
            ['name', 'string', 'max' => 45],
            ['cmdsDenied', 'in', 'range' => array_keys($this->getFileOperationItem())],
            ['downloadEnable', 'in', 'range' => array_keys($this->getDownloadStatusItem())],
            ['writeEnable', 'in', 'range' => array_keys($this->getWriteEnableStatusItem())],
            ['localRoot', 'string'],
            ['setting', 'safe'],
        ];
    }

    public function behaviors() {
        return [
            [
                'class'      => AttributeBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => [null],
                    self::EVENT_BEFORE_UPDATE => [null],
                ],
                'value'      => function ($event) {
                    /* @var User $model */
                    $model          = $event->sender;
                    $model->setting = Json::encode($this->settingBuild());
                }
            ],
            [
                'class'      => AttributeBehavior::className(),
                'attributes' => [
                    self::EVENT_AFTER_FIND => [null],
                ],
                'value'      => function ($event) {
                    /* @var User $model */
                    $model = $event->sender;
                    $model->setAttributes($model->settingParse());
                }
            ],
        ];
    }


    public function attributeLabels() {
        return [
            'username'       => '用户名',
            'password'       => '密码',
            'name'           => '昵称',
            'localRoot'      => '用户根目录',
            'downloadEnable' => '下载权限',
            'writeEnable'    => '写入权限',
            'cmdsDenied'     => '文件操作权限',
        ];
    }

    public function attributeHints() {
        return [
            'writeEnable' => '包含删除、创建、上传、重命名、移动，可以单独设置拒绝某个操作',
        ];
    }

    public function getFileOperationItem() {
        return ['DELE' => '删除文件', 'MKD' => '创建文件夹', 'RMD' => '删除文件夹', 'RNFR' => '重命名文件', 'RNTO' => '移动文件'];
    }

    public function getDownloadStatusItem() {
        return [1 => '允许', 0 => '拒绝'];
    }

    public function getWriteEnableStatusItem() {
        return [1 => '允许', 0 => '拒绝'];
    }

    public function settingParse() {
        $result = [];
        foreach ($this->setting ? Json::decode($this->setting) : [] as $k => $v) {
            $k          = preg_replace_callback('#_(\w)#', function ($match) {
                return strtoupper($match[1]);
            }, $k
            );
            $result[$k] = $v;
        }

        return $result;
    }

    public function settingBuild() {
        return ['download_enable' => $this->downloadEnable, 'write_enable' => $this->writeEnable, 'local_root' => (string)$this->localRoot, 'cmds_denied' => $this->cmdsDenied];
    }

    /**
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     * @return string
     */
    public function buildConfigBySetting() {
        $setting                    = Json::decode($this->setting);
        $setting['download_enable'] = $setting['download_enable'] ? 'YES' : 'NO';
        $setting['write_enable']    = $setting['write_enable'] ? 'YES' : 'NO';
        $setting['cmds_denied']     = implode(',', $setting['cmds_denied'] ?: []);
        $data                       = [];
        foreach (array_filter($setting) as $k => $v) {
            $data[] = "{$k}={$v}";
        }

        return implode("\n", $data);
    }
}