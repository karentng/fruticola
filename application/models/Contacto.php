<?php

class Contacto extends MyModel
{
    static $table_name = "contacto";

    static $alias_attribute = array(
        'departamento'  => 'departamento_id',
        'municipio'     => 'municipio_id',
    );
}