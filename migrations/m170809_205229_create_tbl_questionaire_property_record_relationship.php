<?php

use yii\db\Migration;

class m170809_205229_create_tbl_questionaire_property_record_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "tbl_questionaire_property_record_property_rel",
            "tbl_questionaire_property_record",
            "property_record_id",
            'tbl_property_record',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            "tbl_questionaire_property_record_cavity_rel",
            "tbl_questionaire_property_record",
            "cavity_form_id",
            'tbl_cavity',
            'id',
            'CASCADE',
            'CASCADE'
        );


    }

    public function safeDown()
    {
        $this->dropForeignKey("tbl_questionaire_property_record_property_rel", "tbl_questionaire_property_record");
        $this->dropForeignKey("tbl_questionaire_property_record_cavity_rel", "tbl_questionaire_property_record");
    }

}
