<?php

class PersonaAsociada extends MyModel
{
    static $table_name = "personaasociada";

    static $alias_attribute = array(
        'confianza' => 'confianza_id'
    );
}