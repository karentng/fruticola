<?php

class TipoConfianza extends ActiveRecord\Model
{
    static $table_name = "tipoconfianza";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}