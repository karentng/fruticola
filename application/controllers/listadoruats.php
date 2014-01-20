<?php


class ListadoRuats extends CI_Controller {

    public function index_old()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");
        //if($this->ion_auth->in_group("Digitador"))
        //    $ruats = Ruat::find_all_by_creador_id(current_user('id'), array('include' => array('bpa','cosecha','observacion')));
        //else
            $ruats = Ruat::all(array('include' => array('bpa','cosecha', 'visita_tipo_productor'  ,'observacion', 'creador')));

        //foreach($ruats as $r) echo $r->observacion->ruta_formulario;
        $this->twiggy->set('isAdmin', $this->ion_auth->in_group('Administrador'));

        $this->twiggy->set("ruats", $ruats);
        $this->twiggy->template("ruat/listado");
        $this->twiggy->display();
    }

    public function index()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");
        $this->twiggy->template("ruat/listadoruats");
        $this->twiggy->display();
    }

    public function eliminar_ruat($ruat_id=NULL) {
        if(!$ruat_id) show_404();
        check_profile($this, "Administrador", "Coordinador");

        Ruat::find($ruat_id)->eliminar();
        
        echo json_encode(array('type'=>'success', 'text' => 'RUAT eliminado exitosamente'));
    }

    public function test()
    {
        $resultSet = Ruat::all(array(

            'joins' => array('productor', 'creador'),
        ));
        echo $resultSet[0]->to_json();
        var_dump(Ruat::connection()->last_query);
    }

    public function datatable()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");

        $offset    = $this->input->get('iDisplayStart');
        $limit     = $this->input->get('iDisplayLength');
        $search    = $this->input->get('sSearch');
        $order_idx = (int)$this->input->get('iSortCol_0');
        $order_dir = $this->input->get('sSortDir_0');
        $limit     = $this->input->get('iDisplayLength');
        $limit     = $this->input->get('iDisplayLength');
        $sEcho     = (int)$this->input->get('sEcho');

        $numTotalResults = ViewListadoRuats::count();
        $options = array();
       
        if($search) {
            $options['conditions'] = array('numero_formulario = ? or nombre_productor ilike ? or creado::text like ? or ingresado_por ilike ?',
                $search, "%$search%", "%$search%", "%$search%");
            $numFilteredResults = ViewListadoRuats::count($options);
        }
        else {
            $numFilteredResults = $numTotalResults;
        }

        if($order_idx>3 || ($order_dir!='asc' && $order_dir!='desc')) show_404();

        $orders = array('numero_formulario','nombre_productor','creado','ingresado_por');
        $options['offset'] = $offset;
        $options['limit']  = $limit;
        $options['order'] = $orders[$order_idx].' '.$order_dir;

        $puedeEliminar = $this->ion_auth->in_group("Coordinador") || $this->ion_auth->in_group("Administrador");

        $rows = array();
        foreach(ViewListadoRuats::all($options) as $item) {
            $url = site_url("ruata/index/".$item->id);
            $actions = "<div class='btn-group'>".
                "<a class='btn btn-sm btn-warning tip' href='$url' title='Registro Único de Usuarios de Asitencia Técnica'>RUAT <i class='i-arrow-right-3'></i></a>";

            if($item->ruta_formulario) {
                $url = site_url("uploads/". $item->ruta_formulario);
                $actions .= "<a class='btn btn-sm btn-info tip' title='Descargar RUAT Escaneado' href='$url' target='_blank'><i class='i-file-download'></i></a>";
            }
            else $actions .= '<a class="btn btn-sm" disabled="disabled"><i class="i-file-download"></i></a>';

            $actions .="</div>";

            $cls = $item->cosecha_id ? 'btn-warning' : 'btn-default';
            $url = site_url("diagnosticosecha/index/$item->id");
            $actions .= " <a class='btn btn-sm $cls tip' href='$url' title='Diagnóstico Manejo de Cosecha'>Cosecha</a>";

            $cls = $item->bpa_id ? 'btn-warning' : 'btn-default';
            $url = site_url("bpa/index/$item->id");
            $actions .= " <a class='btn btn-sm $cls tip' href='$url' title='Buenas Prácticas Agropecuarias'>BPA</a>";

            $cls = $item->bpa_id ? 'btn-warning' : 'btn-default';
            $url = site_url("bpa/index/$item->id");
            $actions .= " <a class='btn btn-sm $cls tip' href='$url' title='Clasificación Productor'>C. Productor</a>";


            
            $btnEliminar = $puedeEliminar? "<button class='btn btn-danger btn-xs tip' title='Eliminar RUAT' onclick='eliminarRuat({$item->id})'>-</button> " :"";
            

            $row = array($btnEliminar . $item->numero_formulario, 
                $item->nombre_productor, 
                $item->creado->format('Y-m-d  H:i'),
                $item->ingresado_por,
                $actions,
            );
            $rows[] = $row;
        }

        $output = array(
            'iTotalRecords' => $numTotalResults,
            'iTotalDisplayRecords' => $numFilteredResults,
            'sEcho' => $sEcho,
            'aaData' => $rows,
        );
        
        echo json_encode($output);
 
    }
}
