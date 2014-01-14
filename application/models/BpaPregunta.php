<?php

class BpaPregunta extends ActiveRecord\Model
{
    static $table_name = "bpa_pregunta";

    static function sortedB()
    {
        return self::all(array('conditions' => "seccion = 'B'", 'order' => 'numeral'));
    }

    static function sortedC()
    {
        return self::all(array('conditions' => "seccion = 'C'", 'order' => 'numeral'));
    }
}