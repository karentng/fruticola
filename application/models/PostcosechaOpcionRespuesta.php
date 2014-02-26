<?php

class PostcosechaOpcionRespuesta extends ActiveRecord\Model
{
    static $table_name = "postcosecha_opcionrespuesta";

    public static function sorted()
    {
        return self::all(array('order' => 'letra'));
    }
}