<?php

class TipoRazonNoPertenecer extends ActiveRecord\Model
{
    static $table_name = "tiporazonnopertenecer";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}