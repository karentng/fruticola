<?php

class TipoCredito extends ActiveRecord\Model
{
    static $table_name = "tipocredito";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}