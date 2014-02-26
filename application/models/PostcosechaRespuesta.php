<?php

class PostcosechaRespuesta extends ActiveRecord\Model
{
    static $table_name = "postcosecha_respuesta";

    static $belongs_to = array(
        array('postcosechapregunta', 'class_name'=>'PostcosechaPregunta', 'foreign_key'=>'pregunta_id'),        
        array('postcosechaopcionrespuesta', 'class_name'=>'PostcosechaOpcionRespuesta', 'foreign_key'=>'opcion_id')
    );

}