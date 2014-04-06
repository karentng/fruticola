<?php

class BpaImprimible extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador","Digitador","Consultas"));
    }
    public function index($ruat_id, $nro_visita) 
    {
        $ruat = Ruat::find($ruat_id);
        $datosBPA = BuenasPracticas::find_by_ruat_id_and_nro_visita($ruat_id, $nro_visita);
        $preguntasB = BpaPregunta::sortedB();
        $preguntasC = BpaPregunta::sortedC();

        $arreglo = $datosBPA->to_array();
        $arreglo['fecha'] = $datosBPA->fecha_visita->format("Y-m-d");

        //var_dump($datosBPA);

        $idsB = extract_prop($preguntasB, 'id');
        $idsC = extract_prop($preguntasC, 'id');      

        $respuestasB = BpaRespuesta::all(array('conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsB), 'order' => 'id'));
        $respuestasC = BpaRespuesta::all(array('conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsC), 'order' => 'id'));
        
        $this->twiggy->set('preguntasB', $preguntasB);
        $this->twiggy->set('preguntasC', $preguntasC);
        $this->twiggy->set('respuestasB', $respuestasB);
        $this->twiggy->set('respuestasC', $respuestasC);
        $this->twiggy->set('datosBPA', $arreglo);
        $this->twiggy->set('ruat',$ruat);
        $this->twiggy->template("bpa/bpaImprimible");
        $this->twiggy->display();
    }
}