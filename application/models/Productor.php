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
        array('renglon_productivo', 'class_name'=>'RenglonProductivo', 'foreign_key'=>'renglon_productivo_id'),
    );

    public function nombre_completo() {
        $res = $this->nombre1;
        if($this->nombre2) $res .= ' '.$this->nombre2;
        $res .= ' '.$this->apellido1;
        if($this->apellido2) $res .= ' '.$this->apellido2;
        return $res;
    }
}