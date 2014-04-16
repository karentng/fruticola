<?php

class SolicitudCredito extends MyModel {

    static $table_name = "solicitud_credito";
    
    static $has_one = array(
        array('conyugue', 'class_name'=>'Conyugue', 'foreign_key' => 'solicitud_id'),
    );
    
    static $has_many = array(
        array('referencias_familiares', 'conditions' => array('tipo = ?' => array(1)), 'class_name'=>'ReferenciaFamiliarPersonal', 'foreign_key' => 'solicitud_id'),
        array('referencias_personales', 'conditions' => array('tipo = ?' => array(2)), 'class_name'=>'ReferenciaFamiliarPersonal', 'foreign_key' => 'solicitud_id'),
        array('referencias_financieras', 'class_name'=>'ReferenciaFinanciera', 'foreign_key' => 'solicitud_id'),
        array('referencias_comerciales', 'class_name'=>'ReferenciaComercial', 'foreign_key' => 'solicitud_id'),
        array('descripcion_inversiones', 'class_name'=>'DescripcionInversion', 'foreign_key' => 'solicitud_id'),
        array('informacion_predios_inversion', 'class_name'=>'PredioInversion', 'foreign_key' => 'solicitud_id'),
        array('ingresos_adicionales', 'class_name'=>'IngresosAdicionales', 'foreign_key' => 'solicitud_id'),
        array('descripcion_bienes', 'class_name'=>'DescripcionBienes', 'foreign_key' => 'solicitud_id'),
    );

}
