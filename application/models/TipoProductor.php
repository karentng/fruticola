<?php

class TipoProductor extends ActiveRecord\Model
{
    static $table_name = "tipoproductor";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}