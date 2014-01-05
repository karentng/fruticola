<?php

class Orgasociada extends ActiveRecord\Model
{
    static $table_name = "orgasociada";

    static $has_many = array(
        array('clases', 'class_name'=>'OrgasociadaClase', 'foreign_key'=>'orgasociada_id'),
        array('beneficios', 'class_name'=>'OrgasociadaBeneficio', 'foreign_key'=>'orgasociada_id'),
    );
}