<?php

class Productor extends ActiveRecord\Model
{
    static $table_name = "productor";

    static $has_one = array(
        array('contacto', 'class_name'=>'Contacto', 'foreign_key'=>'productor_id'),
        array('economia', 'class_name'=>'Economia', 'foreign_key'=>'productor_id'),
    );
}