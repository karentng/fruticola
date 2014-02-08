<?php
 
class Finca extends MyModel
{
    static $table_name = "finca";

    static $belongs_to = array(
        array('municipio', 'class_name'=>'Municipio', 'foreign_key' => 'municipio_id'),
        array('tenencia', 'class_name'=>'Tenencia', 'foreign_key'=> 'tenencia_id'),
        array('tipo_via', 'class_name'=>'TipoVia', 'foreign_key'=>'via_tipo_id'),
        array('via_estado', 'class_name'=>'TipoEstadoVia', 'foreign_key'=>'via_estado_id')

    );

    static $has_many = array(
        array('finca_servicio', 'class_name'=>'FincaServicio', 'foreign_key' => 'finca_id'),
        array('finca_transporte', 'class_name'=>'FincaTransporte', 'foreign_key' => 'finca_id'),
        array('finca_maquinaria', 'class_name'=>'FincaMaquinaria', 'foreign_key' => 'finca_id')
    );
}