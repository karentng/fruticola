<?php

class Departamento extends ActiveRecord\Model
{
    static $table_name = "departamento";

    static $has_many = array(
        array('municipios', 'class_name' => 'Municipio', 'foreign_key' => 'departamento_id')
    );
}