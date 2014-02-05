<?php

use ActiveRecord\ActiveRecordException;

class Economia extends MyModel
{
    static $table_name = "economia";


    static $belongs_to = array(
        array('tipo_credito', 'class_name'=>'TipoCredito', 'foreign_key'=>'credito_id')
    );

    /*
    static $alias_attribute = array(
        'ingresoMensual'       => 'ingreso_familiar',
        'personasCargo'        => 'personas_dependientes',
        'ingresoAgropecuaria'  => 'ingreso_agropecuario',
        'procedenciaCredito'   => 'credito_id',
        'otroCredito'          => 'otro_credito'
    );
    */
}
