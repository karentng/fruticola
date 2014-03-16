<?php

class RuatEstudioSuelo extends ActiveRecord\Model
{
    static $table_name = "ruat_estudiosuelo";

    static $belongs_to = array(
        array('ruat', 'class_name'=>'Ruat', 'foreign_key'=>'ruat_id'),
    );
}