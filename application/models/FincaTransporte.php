<?php
 
class FincaTransporte   extends ActiveRecord\Model
{
    static $table_name = "finca_transporte";

    static $belongs_to = array(
        array('tipo_transporte', 'class_name'=>'TipoMedioTransporte', 'foreign_key'=>'transporte_id')
    );

}