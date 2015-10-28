<?php

use yii\db\Schema;
use yii\db\Migration;

class m150828_095054_init extends Migration
{
    public function up() {
        $this->createTable('%vsftpd_user', [
                'id'       => 'pk',
                'username' => Schema::TYPE_STRING . '(45) NOT NULL',
                'password' => Schema::TYPE_STRING . '(45) NOT NULL',
                'name'     => Schema::TYPE_STRING . '(45) NOT NULL',
                'setting'     => Schema::TYPE_TEXT . ' NULL',
            ]
        );
        $this->createIndex('username', 'vsftpd_user', 'username', true);

        return true;
    }

    public function down() {
        $this->dropTable('IF EXISTS `vsftpd_user`');

        return true;
    }
}
