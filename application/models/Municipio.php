<?php

class Municipio extends ActiveRecord\Model
{
    static $table_name = "municipio";
    
    static $has_many = array(
        array('municipios_uaf', 'class_name'=>'MunicipioUAF', 'foreign_key' => 'municipio_id'),
    );

}