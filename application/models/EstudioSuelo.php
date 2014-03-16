<?php

class EstudioSuelo extends ActiveRecord\Model
{
    static $table_name = "estudiosuelo";

    static $belongs_to = array(
        array('municipio', 'class_name'=>'Municipio', 'foreign_key'=>'municipio_id'),
    );

    static $has_many = array(
        array('ruats', 'class_name'=>'RuatEstudioSuelo', 'foreign_key'=>'estudio_id'),
    );
}