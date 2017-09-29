<?php

use yii\db\Migration;

class m170822_091420_create_consultant_creator extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }
        $this->createTable('tbl_user_creator', [
            'id' => $this->primaryKey(),
            'creator_id' => $this->integer(),
            'agent_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('tbl_user_creator');
    }

}
