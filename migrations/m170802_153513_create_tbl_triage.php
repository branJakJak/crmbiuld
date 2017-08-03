<?php

use yii\db\Migration;

class m170802_153513_create_tbl_triage extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_triage', [
            'id'            => $this->primaryKey(),
            'material_file_name' => $this->string(),
            'property_record' => $this->integer(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_triage');
    }

}
