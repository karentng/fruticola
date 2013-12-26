<?php

class TipoVende extends ActiveRecord\Model
{
    static $table_name = "tipovende";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}