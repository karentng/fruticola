<?php

class TipoInnovacion extends ActiveRecord\Model
{
    static $table_name = "tipoinnovacion";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}