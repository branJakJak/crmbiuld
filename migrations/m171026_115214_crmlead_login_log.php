<?php

use yii\db\Migration;

class m171026_115214_crmlead_login_log extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        
        }
        $this->createTable('{{%crmlead_login_log}}', [
            'id' => $this->primaryKey(),
        	'user_id' => $this->integer(),
        	'other_information' => $this->text(),
        	'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function safeDown()
    {
    	$this->dropTable('{{%crmlead_login_log}}');
    }


}
