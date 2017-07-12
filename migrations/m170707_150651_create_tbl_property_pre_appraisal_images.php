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
            'date_created'  => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'date_updated'  => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }
    public function safeDown()
    {
        $this->dropTable('tbl_property_pre_appraisal_images');
    }
}
