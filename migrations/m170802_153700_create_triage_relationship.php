<?php

use yii\db\Migration;

class m170802_153700_create_triage_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "triage_relationship",
            "tbl_triage",
            "property_record",
            "tbl_property_record",
            "id",
            "CASCADE",
            "CASCADE"
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('triage_relationship', 'tbl_triage');
    }

}
