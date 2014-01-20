<?php
 
class Finca extends MyModel
{
    static $table_name = "finca";

    static $belongs_to = array(
        array('municipio', 'class_name'=>'Municipio', 'foreign_key' => 'municipio_id'),
    );
}