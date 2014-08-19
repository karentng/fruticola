<?php

class PlanDeNegocio extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador", "Digitador", "Consultas"));
    }

    private function guardarObjNegocio($ruat_id) 
    {
        $negocio = PlanNegocio::find_by_ruat_id($ruat_id);
        if(!$negocio) {
            $negocio = new PlanNegocio;
            $negocio->ruat_id = $ruat_id;
            $negocio->creador_id = current_user('id');
        }
        else {
            PlanNegocioRespuesta::delete_all(array('conditions' => array('plannegocio_id'=>$negocio->id)));
        }

        $negocio->negocio_anios = $this->input->post('negocio_anios');
        $negocio->negocio_meses = $this->input->post('negocio_meses');
        $negocio->cultivo_anios = $this->input->post('cultivo_anios');
        $negocio->cultivo_meses = $this->input->post('cultivo_meses');
        $negocio->fecha_siembra = $this->input->post('fecha_siembra');
        $negocio->area_cultivo = $this->input->post('area_cultivo');
        $negocio->trabajadores = $this->input->post('trabajadores');
        $negocio->fecha_visita = $this->input->post('fecha_visita');
        $negocio->observaciones = $this->input->post('observaciones');
        $negocio->save();
        return $negocio;
    }

    private function redireccionar()
    {
        $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'Formulario Plan de Negocio guardado exitosamente'));
        redirect('listadoruats');
    }

    public function index($ruat_id=NULL)
    {
        if(!$ruat_id) show_404();

        $preguntas = PlanNegocioPregunta::all(array(order=>'numero', 'include'=> 'opciones_respuesta'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('negocio_anios', '', 'required|integer');
        $this->form_validation->set_rules('negocio_meses', '', 'required|integer');
        $this->form_validation->set_rules('cultivo_anios', '', 'required|integer');
        $this->form_validation->set_rules('cultivo_meses', '', 'required|integer');
        $this->form_validation->set_rules('fecha_siembra', '', 'required');
        $this->form_validation->set_rules('area_cultivo', '', 'required|number');
        $this->form_validation->set_rules('trabajadores', 'Número de trabajadores', 'required|integer');
        $this->form_validation->set_rules('fecha_visita', 'Fecha de Visita', 'required');
        $this->form_validation->set_rules('observaciones');

        foreach($preguntas as $preg) {
            $this->form_validation->set_rules("preg_{$preg->id}[]", "", "required");
        }            

        if($this->form_validation->run()) {
            $negocio = $this->guardarObjNegocio($ruat_id);
            foreach($preguntas as $preg) {
                $res_ids = $this->input->post("preg_{$preg->id}");
                foreach($res_ids as $res_id) {
                    PlanNegocioRespuesta::create(array(
                        'plannegocio_id'   => $negocio->id,
                        'pregunta_id'  => $preg->id,
                        'opcion_id'    => $res_id));
                }
            }
            $this->redireccionar();
        }
        else if(validation_errors())
            $this->twiggy->set('notif',array('type'=>'error', 
                'text'=> "Debe responder todas las preguntas. <br> Revise los recuadros rojos"));

        $bloques = array(
            0 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3)),
            1 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3)),
            2 => array(array('inicio' => 0, 'fin' => 0), array('inicio'=>1, 'fin'=>1)),
            3 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7), array('inicio'=>8, 'fin'=>9)),
            4 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>2)),
            5 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            6 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>4)),
            7 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3)),
            8 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            9 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3)),
            10=> array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            11=> array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>4)),
        );

        $productor = Productor::find_by_id(Ruat::find($ruat_id)->productor_id, array('include' => array('tipo_documento')));
        $respuestas_bd = array();       
        $negocio = PlanNegocio::find_by_ruat_id($ruat_id);
        if($negocio) { //ya existe, cargar datos previamente guardados
            $resps = PlanNegocioRespuesta::find_all_by_plannegocio_id($negocio->id);
            
            $respuestas_bd = extract_prop($resps, 'opcion_id');

            $this->twiggy->set('soloLectura', $negocio->soloLectura($this));
        }
        else {
            $negocio = array();
        }

        $ruat = Ruat::find($ruat_id);
        $this->twiggy->set('ruat', $ruat);
        $this->twiggy->set("preguntas", $preguntas);
        $this->twiggy->set("bloques", $bloques);
        $this->twiggy->set("respuestas_bd", $respuestas_bd);
        $this->twiggy->set("negocio", $negocio);
        $this->twiggy->template("plandenegocio/plandenegocio");
        $this->twiggy->display();
    }
}