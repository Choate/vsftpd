<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choateunit\vsftpd\models;

use choate\vsftpd\models\User;
use choateunit\vsftpd\TestCase;

/**
 * Class UserTest
 * @package choateunit\vsftpd\models
 * @author Choate <choate.yao@gmail.com>
 */
class UserTest extends TestCase
{
    /**
     * @var User
     * @author Choate <choate.yao@gmail.com>
     */
    protected $model;


    protected function setUp() {
        self::mockApplication($this->getConfig());
        $this->loadFixture('user');
        $this->model = new User();
    }

    public function testCreate() {
        $model = clone $this->model;
        $this->assertTrue($model->load(['username' => 'testyao', 'password' => '123456', 'downloadEnable' => 0, 'writeEnable' => 1, 'localRoot' => '/data/data1/video'], ''));
        $this->assertTrue($model->save(true));
        $this->assertJson($model->setting);
        $this->assertTrue($model->find()->andWhere(['username' => 'testyao'])->exists());
        $this->assertTrue($model->refresh());
        $model->username = 'testYao';
        $this->assertFalse($model->update());
        $this->assertCount(1, $model->getErrors());
        $this->assertArrayHasKey('username', $model->getErrors());
        $model->username = '1test';
        $this->assertFalse($model->update());
        $this->assertCount(1, $model->getErrors());
        $this->assertArrayHasKey('username', $model->getErrors());
        $model->username = 'test1';
        $this->assertEquals(1, $model->update());
    }

    public function testFind() {
        /* @var User $m */
        $m = $this->model->find()->andWhere(['username' => 'choateyao'])->one();
        $this->assertTrue($m->downloadEnable === 0);
        $this->assertTrue($m->writeEnable === 1);
        $this->assertAttributeEquals('/data/data1/video', 'localRoot', $m);
        $this->assertAttributeEmpty('cmdsDenied', $m);
    }

    /**
     * @since 1.0
     * @author Choate <choate.yao@gmail.com>
     */
    public function testUpdate() {
        /* @var User $m */
        $m = $this->model->find()->andWhere(['username' => 'choateyao'])->one();
        $m->downloadEnable = 1;
        $m->save();
        $m = $this->model->find()->andWhere(['username' => 'choateyao'])->one();
        $this->assertEquals(1, $m->downloadEnable);
    }

    public function testBuildConfigBySetting() {
        /* @var User $model */
        $model = $this->model->find()->andWhere(['username' => 'choateyao'])->one();
        $config = explode("\n", $model->buildConfigBySetting());
        $this->assertGreaterThanOrEqual(3, count($config));
        foreach ($config as $value) {
            list($k, $v) = explode('=', $value);
            $this->assertContains($k, ['download_enable', 'write_enable', 'local_root', 'cmds_denied']);
            switch ($k) {
                case 'download_enable' :
                case 'write_enable' :
                    $this->assertContains($v, ['YES', 'NO']);
                    break;
                case 'local_root' :
                    $this->assertEquals('/data/data1/video', $v);
                    break;
                case 'cmds_denied' :
                    $this->assertContains(explode(',', $v), $model->getFileOperationItem());
                    break;
            }
        }
    }
    
}
