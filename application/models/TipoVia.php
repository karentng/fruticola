<?php

class TipoVia extends ActiveRecord\Model
{
    static $table_name = "tipovia";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}