<?php

class PlanNegocio extends MyModel
{
    static $table_name = "plannegocio";
    static $has_many = array(
        array('respuestas', 'class_name' => 'PlanNegocioRespuesta', 'foreign_key' => 'plannegocio_id', 'order' => 'pregunta_id desc')
    );

    /*
    public function eliminar()
    {
        BpaRespuesta::delete_all(array('conditions' => array('bpa_id' => $this->id)));
        $this->delete();
    }
    */
}