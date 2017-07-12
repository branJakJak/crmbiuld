<?php

use yii\db\Migration;

class m170707_150716_create_tbl_property_notes extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_property_notes', [
            'id'            => $this->primaryKey(),
            'property_id'   => $this->integer(),
            'content'       => $this->text(),
            'created_by'    => $this->integer(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_notes');
    }

}
