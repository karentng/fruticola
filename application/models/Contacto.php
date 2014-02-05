<?php

class Contacto extends MyModel
{
    static $table_name = "contacto";



    static $belongs_to = array(
        array('departamento', 'class_name'=>'Departamento', 'foreign_key'=>'departamento_id'),
        array('municipio', 'class_name'=>'Municipio', 'foreign_key'=>'municipio_id')        
    );
    /*
    static $alias_attribute = array(
        'departamento'  => 'departamento_id',
        'municipio'     => 'municipio_id',
    );
    */
}