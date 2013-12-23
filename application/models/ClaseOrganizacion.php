<?php

class ClaseOrganizacion extends ActiveRecord\Model
{
    static $table_name = "claseorganizacion";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}