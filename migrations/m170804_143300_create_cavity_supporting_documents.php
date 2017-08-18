<?php

use yii\db\Migration;

class m170804_143300_create_cavity_supporting_documents extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('tbl_cavity_supporting_document', [
            'id'            => $this->primaryKey(),
            'cavity_form_id' => $this->integer(),
            'type' => $this->string(),
            'document_name' => $this->string(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime(),
        ],$tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('tbl_cavity_supporting_document');
    }
}
