<?php

class Formularios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        $fecha_inicio = $this->input->post('fecha_inicio');
        if($fecha_inicio) $fecha_inicio = $this->db->escape($fecha_inicio);
        $fecha_fin = $this->input->post('fecha_fin');
        if($fecha_fin) $fecha_fin = $this->db->escape($fecha_fin);

        $filtro_fecha = "true";
        if($fecha_inicio) $filtro_fecha .=" and X.creado>=$fecha_inicio";
        if($fecha_fin) $filtro_fecha .=" and X.creado<=$fecha_fin";

        $filtro_renglon ="true";
        if($this->input->post('renglon'))
            $filtro_renglon = "renglon_productivo_id=".$this->db->escape($this->input->post('renglon'));

        $sql = "
            select concat(U.first_name,' ',U.last_name) as usuario,
                ruats, cosechas, bpas, vtps, postcosechas
            from users U
            left join (
                select X.creador_id, count(*) as ruats 
                from ruat X join productor P on P.id=X.productor_id 
                where $filtro_fecha and $filtro_renglon 
                group by X.creador_id) R on R.creador_id=U.id
            left join (
                select X.creador_id, count(*) as cosechas 
                from cosecha X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
                where $filtro_fecha and $filtro_renglon 
                group by X.creador_id) COS on COS.creador_id=U.id
            left join (
                select X.creador_id, count(*) as bpas 
                from bpa X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
                where nro_visita=0 and $filtro_fecha and $filtro_renglon 
                group by X.creador_id) BPA on BPA.creador_id=U.id
            left join (
                select X.creador_id, count(*) as vtps 
                from visita_tipo_productor X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
                where $filtro_fecha and $filtro_renglon 
                group by X.creador_id) VTP on VTP.creador_id=U.id
            left join (
                select X.creador_id, count(*) as postcosechas 
                from postcosecha X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
                where $filtro_fecha and $filtro_renglon 
                group by X.creador_id) POS on POS.creador_id=U.id
            where coalesce(ruats,cosechas,bpas,vtps,postcosechas) is not null
        ";
        //die($sql);

        $result = $this->db->query($sql);

        $totales = array('ruats'=>0,'cosechas'=>0,'bpas'=>0,'vtps'=>0,'postcosechas'=>0);
        foreach($result->result() as $row) {
            if($row->ruats) $totales['ruats'] += $row->ruats;
            if($row->cosechas) $totales['cosechas'] += $row->cosechas;
            if($row->bpas) $totales['bpas'] += $row->bpas;
            if($row->vtps) $totales['vtps'] += $row->vtps;
            if($row->postcosechas) $totales['postcosechas'] += $row->postcosechas;
        }

        $footer = "<tfoot><tr>".
            "<th>Totales (todas las páginas)</th>".
            "<th>$totales[ruats]</th>".
            "<th>$totales[cosechas]</th>".
            "<th>$totales[bpas]</th>".
            "<th>$totales[vtps]</th>".
            "<th>$totales[postcosechas]</th></tr></tfoot>";
        
        $this->load->library('table');
        $this->table->set_heading("Usuario","RUAT", "Cosecha", "BPA", "C. Productor", "Poscosecha");
        $this->table->set_template(array(
            'table_open'=>"<table id='reporteFormularios' class='table table-bordered'>",
            'table_close'=> $footer."</table>"
        ));
        $tabla = $this->table->generate($result);

        $renglones = assoc(RenglonProductivo::sorted(), 'id', 'descripcion');
        $renglones = array('' => '(Todos)') + $renglones;


        $this->twiggy->set('renglones', $renglones);
        $this->twiggy->set('tabla',$tabla);
        $this->twiggy->template('reportes/formularios');
        $this->twiggy->display();
    }

    /*
    public function index2()
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

        $this->twiggy->template('reportes/formularios');
        $this->twiggy->display();
    }
    */

}