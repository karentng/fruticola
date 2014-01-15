<?php

class ReporteFormularios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        check_profile($this, "Administrador", "Coordinador");
    }

    public function index()
    {
        
        $query = array('conditions' => array());

        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin    = $this->input->post('fecha_fin');
        if($fecha_inicio && $fecha_fin)
            $query['conditions'] = array('creado >= ? and creado < ?', $fecha_inicio, (new DateTime($fecha_fin))->modify('+1 day') );
        elseif($fecha_inicio)
            $query['conditions'] = array('creado >= ?', $fecha_inicio);
        elseif($fecha_fin)
            $query['conditions'] = array('creado < ?', (new DateTime($fecha_fin))->modify('+1 day'));
        

        $porUsuario = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0);
        $totales = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0);

        foreach(Ruat::all($query) as $ruat)           { $porUsuario[$ruat->creador_id]['ruat']++;        $totales['ruat']++; }
        foreach(Cosecha::all($query) as $cosecha)     { $porUsuario[$cosecha->creador_id]['cosecha']++;  $totales['cosecha']++; }
        foreach(BuenasPracticas::all($query) as $bpa) { $porUsuario[$bpa->creador_id]['bpa']++;          $totales['bpa']++; } 

        $this->twiggy->set('usuarios', Usuario::all());
        $this->twiggy->set('porUsuario', $porUsuario);
        $this->twiggy->set('totales', $totales);
        $this->twiggy->template('reporteformularios/reporteformularios');
        $this->twiggy->display();
    }
}