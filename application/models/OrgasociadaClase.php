<?php

class OrgasociadaClase extends ActiveRecord\Model
{
    static $table_name = "orgasociada_clase";

    static $belongs_to = array(
        array('categoria', 'class_name'=>'ClaseOrganizacion', 'foreign_key'=>'clase_id')
    );
}