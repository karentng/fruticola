<?php
 
class FincaServicio  extends ActiveRecord\Model
{
    static $table_name = "finca_servicio";


    static $belongs_to = array(
        array('tipo_servicio', 'class_name'=>'TipoServicioPublico', 'foreign_key' => 'servicio_id')
    );

}