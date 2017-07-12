<?php

use yii\db\Migration;

class m170707_150651_create_tbl_property_pre_appraisal_images extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_property_pre_appraisal_images', [
            'id'            => $this->primaryKey(),
            'property_id'            => $this->integer(),
            'image_name'            => $this->string(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime(),
        ]);
    }
    public function safeDown()
    {
        $this->dropTable('tbl_property_pre_appraisal_images');
    }
}
