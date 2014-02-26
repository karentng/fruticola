<?php

class PostcosechaPregunta extends ActiveRecord\Model
{
    static $table_name = "postcosecha_pregunta";

    static $has_many = array(
        array('opciones_respuesta', 'class_name' => 'PostcosechaOpcionRespuesta', 'foreign_key' => 'pregunta_id', 'order' => 'letra'),
    );

    public static function sorted()
    {
        return self::all(array('order' => 'numero'));
    }


}