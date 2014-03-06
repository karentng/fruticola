<?php

class BuenasPracticas extends MyModel
{
    static $table_name = "bpa";
    static $has_many = array(
        array('respuestas', 'class_name' => 'BpaRespuesta', 'foreign_key' => 'bpa_id', 'order' => 'pregunta_id desc')
    );

    public function eliminar()
    {
        BpaRespuesta::delete_all(array('conditions' => array('bpa_id' => $this->id)));
        $this->delete();
    }
}