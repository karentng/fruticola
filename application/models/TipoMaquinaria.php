<?php

class TipoMaquinaria extends ActiveRecord\Model
{
    static $table_name = "tipomaquinaria";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}