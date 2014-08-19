<?php

class PlanNegocioPregunta extends ActiveRecord\Model
{
    static $table_name = "plannegocio_pregunta";

    static $has_many = array(
        array('opciones_respuesta', 'class_name' => 'PlanNegocioOpcionRespuesta', 'foreign_key' => 'pregunta_id', 'order' => 'letra'),
    );

    static function sorted()
    {
        return self::all(array('order' => 'numero'));
    }

}