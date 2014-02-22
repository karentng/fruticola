<?php

class CosechaImprimible extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador","Digitador","Consultas"));
    }
    public function index($ruat_id) 
    {
    	$ruat = Ruat::find($ruat_id);
    	$this->twiggy->set('ruat',$ruat);
    	$this->twiggy->template("diagnosticosecha/cosechaImprimible");
        $this->twiggy->display();
    }
}