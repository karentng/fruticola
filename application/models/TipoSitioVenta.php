<?php

class TipoSitioVenta extends ActiveRecord\Model
{
    static $table_name = "tipositioventa";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}