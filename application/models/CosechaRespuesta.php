<?php

class CosechaRespuesta extends ActiveRecord\Model
{
    static $table_name = "cosecha_respuesta";

    static $belongs_to = array(
        array('cosechapregunta', 'class_name'=>'CosechaPregunta', 'foreign_key'=>'pregunta_id'),        
        array('cosechaopcionrespuesta', 'class_name'=>'CosechaOpcionRespuesta', 'foreign_key'=>'opcion_id')
    );

}