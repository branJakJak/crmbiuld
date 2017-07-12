<?php

use yii\db\Migration;

class m170707_150638_create_tbl_property_documents extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_property_documents', [
            'id'            => $this->primaryKey(),
            'property_id'   => $this->integer(),
            'document_name' => $this->string(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_documents');
    }

}
