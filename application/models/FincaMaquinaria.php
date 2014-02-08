<?php
 
class FincaMaquinaria    extends ActiveRecord\Model
{
    static $table_name = "finca_maquinaria";


    static $belongs_to = array(
        array('tipo_maquinaria', 'class_name'=>'TipoMaquinaria', 'foreign_key' => 'maquinaria_id')
    );

}