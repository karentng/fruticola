<?php

class TipoBeneficio extends ActiveRecord\Model
{
    static $table_name = "tipobeneficio";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}