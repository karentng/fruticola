<?php

class TipoActividadVisita extends ActiveRecord\Model
{
    static $table_name = "tipoactividadvisita";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'categoria, orden'));
    }
    
}

