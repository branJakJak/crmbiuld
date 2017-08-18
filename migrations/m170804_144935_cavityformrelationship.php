<?php

use yii\db\Migration;

class m170804_144935_cavityformrelationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "cavityform_rel",
            "tbl_cavity_supporting_document",
            "cavity_form_id",
            'tbl_cavity',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey("cavityform_rel", "tbl_cavity_supporting_document");
    }
}
