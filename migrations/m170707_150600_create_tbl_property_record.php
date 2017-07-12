<?php

use yii\db\Migration;

class m170707_150600_create_tbl_property_record extends Migration
{
    public function safeUp()
    {
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
       ]);
    }

    public function safeDown()
    {
        $this->dropTable('tbl_property_record');
    }

}
