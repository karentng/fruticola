<?php

class Ruat extends ActiveRecord\Model
{
    static $table_name = "ruat";

    static $belongs_to = array(
        array('productor', 'class_name'=>'Productor', 'foreign_key'=>'productor_id'),
        array('asociado', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'asociado_id'),
        array('seguir', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'seguir_id'),
    );

    static $has_one = array(
        array('bpa', 'class_name'=>'BuenasPracticas', 'foreign_key' => 'ruat_id'),
        array('cosecha', 'class_name'=>'Cosecha', 'foreign_key' => 'ruat_id'),

    );

    public function soloLectura(&$controller)
    {
        if($controller->ion_auth->in_group('Digitador')) {
            $dt = new DateTime();
            $tm2 = $dt->getTimestamp();
            $tm1 = $this->creado->getTimestamp();
            $dif_horas = ($tm2-$tm1)/60.0/60.0;
            //echo "diferencia ".$dif_horas;
            //die();
            if($dif_horas>=5.0) {
                return true;
            }
        }
        return false;
    }
}