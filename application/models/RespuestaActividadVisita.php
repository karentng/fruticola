<?php

class RespuestaActividadVisita extends ActiveRecord\Model
{
    static $table_name = "respuestactividadvisita";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}
