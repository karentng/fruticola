<?php

class PlanNegocioRespuesta extends ActiveRecord\Model
{
    static $table_name = "plannegocio_respuesta";

    static $belongs_to = array(
        array('pregunta', 'class_name'=>'PlanNegocioPregunta', 'foreign_key'=>'pregunta_id')
    );
}