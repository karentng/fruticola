<?php

class TipoProductor extends ActiveRecord\Model
{
    static $table_name = "tipoproductor";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}