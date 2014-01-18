<?php

class Cosecha extends MyModel
{
    static $table_name = "cosecha";

    public function eliminar()
    {
        CosechaRespuesta::delete_all(array('conditions' => array('cosecha_id' => $this->id)));
        $this->delete();
    }

    
}