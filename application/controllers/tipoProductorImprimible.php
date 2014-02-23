<?php

class tipoProductorImprimible extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador","Digitador","Consultas"));
    }
    public function index($ruat_id) 
    {
    	$ruat = Ruat::find($ruat_id);
        $tpcpreguntas = TPCPregunta::find('all');
    	$this->twiggy->set('ruat',$ruat);
        $this->twiggy->set('tpcpreguntas',$tpcpreguntas);
    	$this->twiggy->template("tipoproductor/tipoProductorImprimible");
        $this->twiggy->display();
    }
}