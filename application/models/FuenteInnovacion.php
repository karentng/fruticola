<?php

class FuenteInnovacion extends ActiveRecord\Model
{
    static $table_name = "fuenteinnovacion";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}