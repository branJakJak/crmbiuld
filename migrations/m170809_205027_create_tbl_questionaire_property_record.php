<?php

use yii\db\Migration;

class m170809_205027_create_tbl_questionaire_property_record extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        
        }
        $this->createTable('tbl_questionaire_property_record', [
            'id' => $this->primaryKey(),
            'property_record_id'=>$this->integer(),
            'cavity_form_id'=>$this->integer(),
            'date_created' => $this->dateTime(),
            'date_updated' => $this->dateTime(),
        ], $tableOptions);
    }
    public function safeDown()
    {
        $this->dropTable('tbl_questionaire_property_record');
    }
}
