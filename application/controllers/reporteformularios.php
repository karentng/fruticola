<?php

class ReporteFormularios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        
        //$query = array('conditions' => array('true '));
        $conditions = array('true ');

        $fecha_inicio = $this->input->post('fecha_inicio');
        $fecha_fin    = $this->input->post('fecha_fin');
        if($fecha_fin) $fecha_fin = new DateTime($fecha_fin);
        //if($fecha_inicio && $fecha_fin)
        //    $query['conditions'] = array('creado >= ? and creado < ?', $fecha_inicio, $fecha_fin->modify('+1 day') );
        if($fecha_inicio) {
            $conditions[0] .= 'and creado >= ?';
            $conditions[] = $fecha_inicio;
        }
        if($fecha_fin) {
            $conditions[0] .= 'and creado < ?';
            $conditions[] = $fecha_fin->modify('+1 day');
        }

        /*$renglon = $this->input->post('renglon');
        if($renglon) {
            $conditions[0] .= "and "
        }*/

        $conditions = array('conditions' => $conditions);
        

        $porUsuario = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0, 'tp' => 0);
        $totales = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0, 'tp' => 0);

        foreach(Ruat::all($conditions) as $ruat)               { $porUsuario[$ruat->creador_id]['ruat']++;        $totales['ruat']++; }
        foreach(Cosecha::all($conditions) as $cosecha)         { $porUsuario[$cosecha->creador_id]['cosecha']++;  $totales['cosecha']++; }
        foreach(BuenasPracticas::all($conditions) as $bpa)     { $porUsuario[$bpa->creador_id]['bpa']++;          $totales['bpa']++; } 
        foreach(VisitaTipoProductor::all($conditions) as $tp)  { $porUsuario[$tp->creador_id]['tp']++;            $totales['tp']++; } 

        $this->twiggy->set('usuarios', Usuario::all());
        $this->twiggy->set('porUsuario', $porUsuario);
        $this->twiggy->set('totales', $totales);

        $renglones = assoc(RenglonProductivo::sorted(), 'id', 'descripcion');
        $renglones = array('' => '(Todos)') + $renglones;
        $this->twiggy->set('renglones', $renglones);

        $this->twiggy->template('reporteformularios/reporteformularios');
        $this->twiggy->display();
    }
}