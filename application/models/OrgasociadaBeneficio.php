<?php

class OrgasociadaBeneficio extends ActiveRecord\Model
{
    static $table_name = "orgasociada_beneficio";

    static $belongs_to = array(
        array('categoria', 'class_name'=>'TipoBeneficio', 'foreign_key'=>'beneficio_id')
    );
}