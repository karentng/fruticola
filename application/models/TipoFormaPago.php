<?php

class TipoFormaPago extends ActiveRecord\Model
{
    static $table_name = "tipoformapago";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}