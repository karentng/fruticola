<?php

class CosechaOpcionRespuesta extends ActiveRecord\Model
{
    static $table_name = "cosecha_opcionrespuesta";

    public static function sorted()
    {
        return self::all(array('order' => 'letra'));
    }
}