<?php

use yii\db\Migration;

class m170707_175755_property_owner_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "property_owner_rel",
            "tbl_property_owner",
            "property_id",
            "tbl_property_record",
            "id",
            "CASCADE",
            "CASCADE"
        );
        $this->addForeignKey(
            "owner_property_rel",
            "tbl_property_owner",
            "owner_id",
            "tbl_owner",
            "id",
            "CASCADE",
            "CASCADE"
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey("property_owner_rel","tbl_property_owner");
        $this->dropForeignKey("owner_property_rel","tbl_property_owner");
    }
}