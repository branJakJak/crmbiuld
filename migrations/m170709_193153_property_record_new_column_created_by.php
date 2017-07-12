<?php

use yii\db\Migration;

class m170709_193153_property_record_new_column_created_by extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            "tbl_property_record",
            "created_by",
            $this->integer()
        );

    }

    public function safeDown()
    {
        $this->dropColumn(
            "tbl_property_record",
            "created_by"
        );

    }
}
