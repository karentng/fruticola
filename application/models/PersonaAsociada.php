<?php

class PersonaAsociada extends MyModel
{
    static $table_name = "personaasociada";

    static $belongs_to = array(
        array('tipo_confianza', 'class_name'=>'TipoConfianza', 'foreign_key'=>'confianza_id')
    );
    /*
    static $alias_attribute = array(
        'confianza' => 'confianza_id'
    );
    */
}