<?php

class MetaComplementariaPregunta extends ActiveRecord\Model
{
    static $table_name = "meta_complementaria_pregunta";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}