<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choateunit\vsftpd\generators;

use choate\vsftpd\generators\GeneratorUserConfig;
use choate\vsftpd\models\User;
use choate\vsftpd\Module;
use choateunit\vsftpd\TestCase;

/**
 * Class GeneratorUserConfigTest
 * @package choateunit\vsftpd\generators
 * @author Choate <choate.yao@gmail.com>
 */
class GeneratorUserConfigTest extends TestCase
{
    /**
     * @var User
     * @author Choate <choate.yao@gmail.com>
     */
    protected $model;

    /**
     * @var GeneratorUserConfig
     *
     * @author Choate <choate.yao@gmail.com>
     */
    protected $generator;

    public function testGenerate() {
        /* @var User $model */
        $model = $this->model->findOne(['username' => 'choateyao']);
        $this->generator->setModel($model);
        $this->generator->setModule(new Module('vsftpd', null, ['vsftpdUserConfigPath' => '/tmp', 'vsftpdUser' => 'choate']));
        $this->assertEquals(strlen($model->buildConfigBySetting()), $this->generator->generate());
        $this->assertFileExists('/tmp/choateyao');
    }

    protected function setUp() {
        self::mockApplication($this->getConfig());
        $this->loadFixture('user');
        $this->model = new User();
        $this->generator = new GeneratorUserConfig();
    }

    protected function tearDown() {
        if (file_exists('/tmp/choateyao')) {
            unlink('/tmp/choateyao');
        }
    }

}
