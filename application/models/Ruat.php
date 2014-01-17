<?php

class Ruat extends MyModel
{
    static $table_name = "ruat";

    static $belongs_to = array(
        array('productor', 'class_name'=>'Productor', 'foreign_key'=>'productor_id'),
        array('asociado', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'asociado_id'),
        array('seguir', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'seguir_id'),
        array('creador', 'class_name'=>'Usuario', 'foreign_key'=>'creador_id'),
    );

    static $has_one = array(
        array('observacion', 'class_name'=>'Observacion', 'foreign_key' => 'ruat_id'),
        array('bpa', 'class_name'=>'BuenasPracticas', 'foreign_key' => 'ruat_id'),
        array('cosecha', 'class_name'=>'Cosecha', 'foreign_key' => 'ruat_id'),
        array('visita_tipo_productor', 'class_name'=>'VisitaTipoProductor', 'foreign_key' => 'ruat_id'),
        array('finca', 'class_name'=>'Finca', 'foreign_key' => 'ruat_id'),
    );
}