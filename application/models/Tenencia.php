<?php

class Tenencia extends ActiveRecord\Model
{
    static $table_name = "tenencia";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}