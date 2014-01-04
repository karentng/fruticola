<?php

class Productor extends ActiveRecord\Model
{
    static $table_name = "productor";

    static $has_one = array(
        array('contacto', 'class_name'=>'Contacto', 'foreign_key'=>'productor_id'),
        array('economia', 'class_name'=>'Economia', 'foreign_key'=>'productor_id'),
    );

    static $alias_attribute = array(
        'nivelEducativo'    => 'nivel_educativo_id',
        'tipoDocumento'     => 'tipo_documento_id',
        'numeroDocumento'   => 'numero_documento',
        'tipo'              => 'tipo_productor_id',
        'renglonProductivo' => 'renglon_productivo_id',
        'fechaNacimiento'   => 'fecha_nacimiento'
    );
}