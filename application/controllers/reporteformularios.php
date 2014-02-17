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
            $conditions[0] .= 'and creado >= ? ';
            $conditions[] = $fecha_inicio;
        }
        if($fecha_fin) {
            $conditions[0] .= 'and creado < ? ';
            $conditions[] = $fecha_fin->modify('+1 day');
        }

        $renglon = $this->input->post('renglon');
        if($renglon) {
            #$conditions[0] .= "and "
            $prods = extract_prop(Productor::find_all_by_renglon_productivo_id($renglon), 'id');
            if(!$prods) 
                $ruats = array(-1);
            else
                $ruats = extract_prop(Ruat::all(array('conditions'=>array('productor_id in (?)',$prods))), 'id');
            $conds_ruat = $conditions;
            $conds_otros = $conditions;
            $conds_ruat[0] .='and id in (?) ';
            $conds_ruat[] = $ruats;
            $conds_otros[0] .='and ruat_id in (?) ';
            $conds_otros[] = $ruats;
        }
        else {
            $conds_ruat = $conditions;
            $conds_otros = $conditions;
        }

        //var_dump($conds_ruat);
        //var_dump($conds_otros);

        #$conditions = array('conditions' => $conditions);
        $conds_ruat = array('conditions' => $conds_ruat);
        $conds_otros = array('conditions' => $conds_otros);
        

        $porUsuario = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0, 'tp' => 0);
        $totales = array('ruat' => 0, 'bpa' => 0, 'cosecha' => 0, 'tp' => 0);

        foreach(Ruat::all($conds_ruat) as $ruat)               { $porUsuario[$ruat->creador_id]['ruat']++;        $totales['ruat']++; }
        foreach(Cosecha::all($conds_otros) as $cosecha)         { $porUsuario[$cosecha->creador_id]['cosecha']++;  $totales['cosecha']++; }
        foreach(BuenasPracticas::all($conds_otros) as $bpa)     { $porUsuario[$bpa->creador_id]['bpa']++;          $totales['bpa']++; } 
        foreach(VisitaTipoProductor::all($conds_otros) as $tp)  { $porUsuario[$tp->creador_id]['tp']++;            $totales['tp']++; } 

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