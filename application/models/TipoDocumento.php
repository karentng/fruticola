<?php

class TipoDocumento extends ActiveRecord\Model
{
    static $table_name = "tipodocumento";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}