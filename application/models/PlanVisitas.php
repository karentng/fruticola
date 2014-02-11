<?php

class PlanVisitas extends ActiveRecord\Model
{
    static $table_name = "planvisitas";

    static function sorted()
    {
        return self::all(array('select' => 'id,descripcion', 'order' => 'orden'));
    }
}
