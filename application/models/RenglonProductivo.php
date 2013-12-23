<?php

class RenglonProductivo extends ActiveRecord\Model
{
    static $table_name = "renglonproductivo";

    static function sorted()
    {
        return self::all(array('order' => 'orden'));
    }
}