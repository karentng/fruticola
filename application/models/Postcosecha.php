<?php

class Postcosecha extends MyModel
{
    static $table_name = "postcosecha";


    static $has_many = array(
        array('postcosecharespuestas', 'class_name'=>'PostcosechaRespuesta', 'foreign_key' => 'postcosecha_id')
    );

    public function eliminar()
    {
        PostcosechaRespuesta::delete_all(array('conditions' => array('postcosecha_id' => $this->id)));
        $this->delete();
    }

    
}