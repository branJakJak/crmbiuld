<?php

use yii\db\Migration;

class m170707_150600_create_tbl_property_record extends Migration
{
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable("tbl_property_record", [
            'id'                        => $this->primaryKey(),
            'insulation_type'           => $this->string(),
            'postcode'                  => $this->string(),
            'address1'                  => $this->string(),
            'address2'                  => $this->string(),
            'address3'                  => $this->string(),
            'zipcode'                   => $this->string(),
            'town'                      => $this->string(),
            'country'                   => $this->string(),
            'property_type'             => $this->string(),
            'number_of_bedrooms'        => $this->string(),
            'approximate_year_of_build' => $this->integer(),
            'date_of_cwi'               => $this->dateTime(),
            'installer'                 => $this->string(),
            'product_installed'         => $this->string(),
            'system_designer'           => $this->string(),
            'guarantee_provider'        => $this->string(),
            'guarantee_number'          => $this->float(),
            'date_guarantee_issued'     => $this->dateTime(),
            'date_created'              => $this->dateTime(),
            'date_updated'              => $this->dateTime()
       ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_record');
    }

}
