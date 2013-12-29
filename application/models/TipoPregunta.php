<?php

class TipoPregunta extends ActiveRecord\Model
{
    static $table_name = "tipopregunta";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}