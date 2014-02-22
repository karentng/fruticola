<?php

class Cosecha extends MyModel
{
    static $table_name = "cosecha";


    static $has_many = array(
        array('cosecharespuestas', 'class_name'=>'CosechaRespuesta', 'foreign_key' => 'cosecha_id')
    );

    public function eliminar()
    {
        CosechaRespuesta::delete_all(array('conditions' => array('cosecha_id' => $this->id)));
        $this->delete();
    }

    
}