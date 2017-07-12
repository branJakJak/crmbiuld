<?php

use yii\db\Migration;

class m170711_114310_add_column_image_Description_property_preappraisal_image extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'tbl_property_pre_appraisal_images',
            'image_description',
                $this->string()
            );
    }

    public function safeDown()
    {
        $this->dropColumn("tbl_property_pre_appraisal_images", 'image_description');
    }
}