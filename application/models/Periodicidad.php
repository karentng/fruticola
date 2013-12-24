<?php

class Periodicidad extends ActiveRecord\Model
{
    static $table_name = "periodicidad";

    static function sorted()
    {
        return self::all(array('order' => 'dias'));
    }
}