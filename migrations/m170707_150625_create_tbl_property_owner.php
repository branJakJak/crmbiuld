<?php

use yii\db\Migration;

class m170707_150625_create_tbl_property_owner extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_property_owner', [
            'id' => $this->primaryKey(),
            'property_id' => $this->integer(),
            'owner_id' => $this->integer(),
            'date_created' => $this->dateTime(),
            'date_updated' => $this->dateTime()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_owner');
    }
}
