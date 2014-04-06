<?php

class BpaRespuesta extends ActiveRecord\Model
{
    static $table_name = "bpa_respuesta";


    static $belongs_to = array(
        array('pregunta', 'class_name'=>'Bpa_pregunta', 'foreign_key'=>'pregunta_id')
    );
}