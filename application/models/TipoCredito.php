<?php

class TipoCredito extends ActiveRecord\Model
{
    static $table_name = "tipocredito";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}