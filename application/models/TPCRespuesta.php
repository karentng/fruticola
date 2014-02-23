<?php

class TPCRespuesta extends ActiveRecord\Model
{
    static $table_name = "tp_c_respuesta";

    static $belongs_to = array(
        array('preguntas', 'class_name'=>'TPCPregunta', 'foreign_key'=>'pregunta_c_id')
    );
}