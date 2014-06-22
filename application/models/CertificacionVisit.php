<?php

class CertificacionVisit extends ActiveRecord\Model
{
    static $table_name = "certificacion_visita";

    static $TITULO_FORMULARIO = array(
        0 => "Elaboración de propuestas asociativas  para estudio de riego",
        1 => "Implementación de prácticas de manejo agronómico  tendientes a romper la estacionalidad",
        2 => "Renovación de cultivos existentes y plantación  de áreas nuevas con variedades promisorias",
        3 => "Mejoramiento de las áreas actuales con semilla  de PIÑA de alta calidad genética y productiva",
        4 => "Implementación de modelos de producción óptima  de MANGO",
        5 => "Mejoramiento de las áreas actuales con semilla  de FRESA de alta calidad genética y productiva",
        6 => "Mejoramiento de las áreas actuales con semilla  de MORA de alta calidad genética y productiva",
        7 => "Planes de fertilidad",
        8 => "Visitas de seguimiento y/o acompañamiento",
        9 => "Elaboración de los planes de negocio regional",
        10=> "Incremento de los rendimientos de cultivo",
        11=> "Estacionalidad de la Producción",
        12=> "Manejo Fitosanitario 1",
        13=> "Manejo Fitosanitario 2",
    );

    
    static function formularios_renglon($renglon_id) 
    {
        $res = array(0,1,2,7,8,9,10,11,12,13);
        if($renglon_id==12) $res[] = 3;
        else if($renglon_id==7) $res[] = 4;
        else if($renglon_id==5) $res[] = 5;
        else if($renglon==10) $res[] = 6;
        return $res;
    }
}