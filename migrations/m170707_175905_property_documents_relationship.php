<?php

use yii\db\Migration;

class m170707_175905_property_documents_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "property_documents_rel",
            "tbl_property_documents",
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
            "property_documents_rel",
            "tbl_property_documents"
        );

    }

}
