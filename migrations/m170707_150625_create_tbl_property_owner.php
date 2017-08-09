<?php

use yii\db\Migration;

class m170707_150625_create_tbl_property_owner extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }        
        $this->createTable('tbl_property_owner', [
            'id' => $this->primaryKey(),
            'property_id' => $this->integer(),
            'owner_id' => $this->integer(),
            'date_created' => $this->dateTime(),
            'date_updated' => $this->dateTime()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_owner');
    }
}
