<?php

class TipoMedioTransporte extends ActiveRecord\Model
{
    static $table_name = "tipomediotransporte";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}