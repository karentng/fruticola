<?php

class VisitaTipoProductor extends MyModel
{
    static $table_name = "visita_tipo_productor";

    public function eliminar()
    {
        TPBRespuesta::delete_all(array('conditions' => array('visita_id' => $this->id)));
        TPCRespuesta::delete_all(array('conditions' => array('visita_id' => $this->id)));
        TPDRespuesta::delete_all(array('conditions' => array('visita_id' => $this->id)));
        $this->delete();
    }
}