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
            'date_created'  => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'date_updated'  => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_notes');
    }

}
