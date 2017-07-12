<?php

use yii\db\Migration;

class m170709_190047_property_record_new_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            "tbl_property_record",
            "status",
            $this->string()
        );
        $this->addColumn(
            "tbl_property_record",
            "appraisal_completed",
            $this->dateTime()
        );
    }

    public function safeDown()
    {
        $this->dropColumn(
            "tbl_property_record",
            "status"
        );
        $this->dropColumn(
            "tbl_property_record",
            "appraisal_completed"
        );
    }


}
