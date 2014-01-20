<?php


class ListadoRuats extends CI_Controller {

    public function index()
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

    public function index2()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");
        $this->twiggy->template("ruat/listadoruats");
        $this->twiggy->display();
    }

    public function eliminar_ruat($ruat_id=NULL) {
        if(!$ruat_id) show_404();
        check_profile($this, "Administrador");


        Ruat::find($ruat_id)->eliminar();
        $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'RUAT eliminado exitósamente'));
        redirect("listadoruats");
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
        check_profile($this, "Administrador");
        //var_dump($this->input->get());

        $offset = $this->input->get('iDisplayStart');
        $limit  = $this->input->get('iDisplayLength');
        $search = $this->input->get('sSearch');
        $order_idx  = (int)$this->input->get('iSortCol_0');
        $order_dir  = $this->input->get('sSortDir_0');
        $limit  = $this->input->get('iDisplayLength');
        $limit  = $this->input->get('iDisplayLength');
        $sEcho = (int)$this->input->get('sEcho');

        
        $numTotalResults = ViewListadoRuats::count();
        

        
        $options = array();


        

        

        
        if($search) {
            $options['conditions'] = array('numero_formulario = ? or nombre_productor ilike ? or creado like ? or ingresado_por ilike ?',
                $search, "%$search%", "%$search%", "%$search%");
            $numFilteredResults = ViewListadoRuats::count($options);
        }
        else {
            $numFilteredResults = $numTotalResults;
        }

        

        if($order_idx>3 || ($order_dir!='asc' && $order_dir!='desc')) show_404();

        $orders = array(
            0 => 'numero_formulario',
            1 => 'nombre_productor',
            2 => 'creado',
            3 => 'ingresado_por'
        );
        $options['offset'] = $offset;
        $options['limit']  = $limit;
        $options['order'] = $orders[$order_idx].' '.$order_dir;


        $rows = array();

        foreach(ViewListadoRuats::all($options) as $item) {
            $row = array($item->numero_formulario, 
                $item->nombre_productor, 
                $item->creado->format('Y-m-d  H:i'),
                $item->ingresado_por,
                'hl'
            );
            $rows[] = $row;
        }
        //var_dump($rows);

        /*if(!$search) {
            $resultSet = Ruat::all(array(
                'offset' =>))
        }*/

        

        $output = array(
            'iTotalRecords' => $numTotalResults,
            'iTotalDisplayRecords' => $numFilteredResults,
            'sEcho' => $sEcho,
            'aaData' => $rows,
        );
        
        echo json_encode($output);
 
    }
}
