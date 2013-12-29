<?php

class TipoRespuesta extends ActiveRecord\Model
{
    static $table_name = "tiporespuesta";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}