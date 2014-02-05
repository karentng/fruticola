<?php

class Municipio extends ActiveRecord\Model
{
    static $table_name = "municipio";


    static $belongs_to = array(
        array('departamento', 'class_name'=>'Departamento', 'foreign_key'=>'departamento_id')
    );
    
    static $has_many = array(
        array('municipios_uaf', 'class_name'=>'MunicipioUAF', 'foreign_key' => 'municipio_id')
    );

}