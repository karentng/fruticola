<?php

class Innovacion extends MyModel
{
    static $table_name = "procesoinnovacion";

    static $belongs_to = array(
        array('actividad', 'class_name'=>'TipoInnovacion', 'foreign_key'=>'tipo_id'),
        array('fuente', 'class_name'=>'FuenteInnovacion', 'foreign_key'=>'fuente_id')
    );
}