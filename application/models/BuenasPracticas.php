<?php

class BuenasPracticas extends MyModel
{
    static $table_name = "bpa";

    public function eliminar()
    {
        BpaRespuesta::delete_all(array('conditions' => array('bpa_id' => $this->id)));
        $this->delete();
    }
}