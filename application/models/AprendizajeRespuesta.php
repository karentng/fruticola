<?php

class AprendizajeRespuesta extends ActiveRecord\Model
{
    static $table_name = "aprendizaje_respuesta";

    static $belongs_to = array(
        array('pregunta', 'class_name'=>'TipoPregunta', 'foreign_key'=>'pregunta_id'),
        array('respuesta', 'class_name'=>'TipoRespuesta', 'foreign_key'=>'respuesta_id')
    );
}