<?php

use yii\db\Migration;

class m170709_181021_property_images_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            "property_image_relationship",
            "tbl_property_images",
            "property_id",
            "tbl_property_record",
            "id",
            "CASCADE",
            "CASCADE"
            );
    }

    public function safeDown()
    {
        $this->dropForeignKey('property_image_relationship', 'tbl_property_images');
    }

}
