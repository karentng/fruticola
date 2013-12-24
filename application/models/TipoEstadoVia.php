<?php

class TipoEstadoVia extends ActiveRecord\Model
{
    static $table_name = "tipoestadovia";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}