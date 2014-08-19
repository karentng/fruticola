<?php

class PlanNegocioOpcionRespuesta extends ActiveRecord\Model
{
    static $table_name = "plannegocio_opcionrespuesta";

    public static function sorted()
    {
        return self::all(array('order' => 'letra'));
    }
}