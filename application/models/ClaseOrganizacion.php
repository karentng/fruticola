<?php

class ClaseOrganizacion extends ActiveRecord\Model
{
    static $table_name = "claseorganizacion";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}