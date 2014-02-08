<?php

class RazonNoPertenecer extends ActiveRecord\Model
{
    static $table_name = "razonnopertenecer";


    static $belongs_to = array(
        array('tipo_razon', 'class_name'=>'TipoRazonNoPertenecer', 'foreign_key'=>'razon_id')
    );
}