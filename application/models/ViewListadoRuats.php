<?php 

class ViewListadoRuats extends ActiveRecord\Model {
    static $table_name = "listadoruats";

    static $has_one = array(
        array('observacion', 'class_name'=>'Observacion', 'foreign_key' => 'ruat_id'),
    );
}