<?php

class Ruat extends ActiveRecord\Model
{
    static $table_name = "ruat";

    static $belongs_to = array(
        array('productor', 'class_name'=>'Productor', 'foreign_key'=>'productor_id'),
        array('asociado', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'asociado_id'),
        array('seguir', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'seguir_id'),
    );

    public function soloLectura(&$controller)
    {
        if($controller->ion_auth->in_group('Digitador')) {
            if($this->creado->diff(new DateTime(),true)->h >= 6) {
                return true;
            }
        }
        return false;
    }
}