<?php

use yii\db\Migration;

class m170804_143300_create_cavity_supporting_documents extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_cavity_supporting_document', [
            'id'            => $this->primaryKey(),
            'cavity_form_id' => $this->integer(),
            'type' => $this->string(),
            'document_name' => $this->string(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime(),
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('tbl_cavity_supporting_document');
    }
}
