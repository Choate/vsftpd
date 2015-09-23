<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
namespace choateunit\vsftpd\migrations;

use choateunit\vsftpd\TestCase;
use yii\helpers\ArrayHelper;

/**
 * Class m150828_095054_initTest
 * @package choateunit\vsftpd\migrations
 * @author Choate <choate.yao@gmail.com>
 */
class m150828_095054_initTest extends TestCase
{
    /**
     * @var \m150828_095054_init;
     *
     * @author Choate <choate.yao@gmail.com>
     */
    protected $obj;

    public function testUp() {
        $this->assertTrue($this->obj->up());
        $columns = ArrayHelper::toArray(\Yii::$app->db->getSchema()->getTableSchema('vsftpd_user'));
        $this->assertArrayHasKey('id', ArrayHelper::getValue($columns, 'columns'));
        $this->assertArrayHasKey('username', ArrayHelper::getValue($columns, 'columns'));
        $this->assertArrayHasKey('password', ArrayHelper::getValue($columns, 'columns'));
        $this->assertArrayHasKey('name', ArrayHelper::getValue($columns, 'columns'));
        $this->assertArrayHasKey('setting', ArrayHelper::getValue($columns, 'columns'));
        $this->assertEquals(1, $columns['columns']['id']['isPrimaryKey']);
        $this->assertEquals('integer', $columns['columns']['id']['type']);
        $this->assertEquals(45, $columns['columns']['username']['size']);
        $this->assertEquals('string', $columns['columns']['username']['type']);
        $this->assertEquals(45, $columns['columns']['password']['size']);
        $this->assertEquals('string', $columns['columns']['password']['type']);
        $this->assertEquals(45, $columns['columns']['name']['size']);
        $this->assertEquals('string', $columns['columns']['name']['type']);
        $this->assertEquals('text', $columns['columns']['setting']['type']);
        $this->assertEquals(1, $columns['columns']['setting']['allowNull']);
    }

    protected function setUp() {
        self::mockApplication($this->getConfig());
        include __DIR__ . '/../../migrations/m150828_095054_init.php';
        $this->obj = new \m150828_095054_init();
        $this->obj->down();
    }

    protected function tearDown() {
        $this->obj->down();
    }
}
