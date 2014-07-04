<?php

class RuatEstudioSuelo extends ActiveRecord\Model
{
    static $table_name = "ruat_estudiosuelo";

    static $belongs_to = array(
        array('ruat', 'class_name'=>'Ruat', 'foreign_key'=>'ruat_id'),
    );

    public static function asociar($ruat_id, $estudio_id)
    {
        $asoc = RuatEstudioSuelo::find_by_ruat_id($ruat_id);
        if($asoc) return false;
        $prev = RuatEstudioSuelo::find_by_estudio_id($estudio_id, array('order' => 'numero DESC'));
        $numero = $prev ? $prev->numero+1 : 1;
        $ruat_est = new RuatEstudioSuelo();
        $ruat_est->ruat_id = $ruat_id;
        $ruat_est->estudio_id = $estudio_id;
        $ruat_est->numero = $numero;
        $ruat_est->save();
        return true;
    }
}