<?php

class ReporteFormularios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        check_profile($this, "Administrador", "Coordinador", "Digitador");
    }

    public function index()
    {
        $fecha_inicio = $fecha_fin = NULL;
        $filtro = array();
        if($this->input->post()) {
            $fecha_inicio = $this->input->post('fecha_inicio');
            $fecha_fin    = $this->input->post('fecha_fin');
        }

        $porUsuario = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0);
        $totales = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0);

        foreach(Ruat::all() as $ruat)           { $porUsuario['ruat']++;      $totales['ruat']++; }
        foreach(Cosecha::all() as $cosecha)     { $formsUsuario['cosecha']++; $totales['cosecha']++; }
        foreach(BuenasPracticas::all() as $bpa) { $formsUsuario['bpa']++;     $totales['bpa']++; } 


        $this->twiggy->set('porUsuario', $porUsuario);
        $this->twiggy->set('totales', $totales);
    }
}