<?php

use yii\db\Migration;

class m170707_175809_property_notes_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "property_notes_rel",
            "tbl_property_notes",
            "property_id",
            "tbl_property_record",
            "id",
            "CASCADE",
            "CASCADE"
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            "property_notes_rel",
            "tbl_property_notes"
        );
    }
}
