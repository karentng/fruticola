<?php

class Productor extends MyModel
{
    static $table_name = "productor";

    static $has_one = array(
        array('contacto', 'class_name'=>'Contacto', 'foreign_key'=>'productor_id'),
        array('economia', 'class_name'=>'Economia', 'foreign_key'=>'productor_id'),
    );

    static $belongs_to = array(
        array('tipo_documento', 'class_name'=>'TipoDocumento', 'foreign_key'=>'tipo_documento_id'),
    );

    public function nombre_completo() {
        $res = $this->nombre1;
        if($this->nombre2) $res .= ' '.$this->nombre2;
        $res .= ' '.$this->apellido1;
        if($this->nombre2) $res .= ' '.$this->apellido2;
        return $res;
    }

    /*
    public function to_array() {
        $res = parent::to_array();
        $res['nombre_completo'] = $this->nombre_completo();
        $res['tipo_documento'] = $this->tipo_documento->descripcion;
        return $res;
    }
    */
}