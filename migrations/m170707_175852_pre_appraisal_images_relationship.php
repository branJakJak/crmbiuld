<?php

use yii\db\Migration;

class m170707_175852_pre_appraisal_images_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "pre_appraisal_image_rel",
            "tbl_property_pre_appraisal_images",
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
            "pre_appraisal_image_rel",
            "tbl_property_pre_appraisal_images"
        );

    }
}
