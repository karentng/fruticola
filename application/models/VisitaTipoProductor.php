<?php

class VisitaTipoProductor extends MyModel
{
    static $table_name = "visita_tipo_productor";

    static $has_many = array(
        array('respuestasproductor', 'class_name'=>'TPCRespuesta', 'foreign_key' => 'visita_id')
    );

    public function eliminar()
    {
        TPBRespuesta::delete_all(array('conditions' => array('visita_id' => $this->id)));
        TPCRespuesta::delete_all(array('conditions' => array('visita_id' => $this->id)));
        TPDRespuesta::delete_all(array('conditions' => array('visita_id' => $this->id)));
        $this->delete();
    }
}