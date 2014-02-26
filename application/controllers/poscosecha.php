<?php

class Poscosecha extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador", "Digitador", "Consultas"));
    }

    private function guardarObjPostcosecha($ruat_id) 
    {
        $postcosecha =Postcosecha::find_by_ruat_id($ruat_id);
        if(!$postcosecha) {
            $postcosecha = new Postcosecha;
            $postcosecha->ruat_id = $ruat_id;
            $postcosecha->creador_id = current_user('id');
        }
        else {
            PostcosechaRespuesta::delete_all(array('conditions' => array('postcosecha_id'=>$postcosecha->id)));
        }

        $postcosecha->observaciones = $this->input->post('observaciones');
        $postcosecha->fecha_visita = $this->input->post('fecha_visita');
        $postcosecha->save();
        return $postcosecha;
    }

    private function redireccionar()
    {
        $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'Formulario Postcosecha guardado exitosamente'));
        redirect('listadoruats');
    } 

    public function index($ruat_id=NULL)
    {
        if(!$ruat_id) show_404();

        $preguntas = PostcosechaPregunta::all(array(order=>'numero', 'include'=> 'opciones_respuesta'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('fecha_visita', 'Fecha de Visita', 'required');
        $this->form_validation->set_rules('observaciones');

        if($this->input->post('accion')=='guardarBlanco') {
            if($this->form_validation->run() && count($this->input->post())==3) { //fecha, observaciones y boton
                $this->guardarObjPostcosecha($ruat_id);
                $this->redireccionar();
            }
            else
                $this->twiggy->set('notif',array('type'=>'error', 
                    'text'=> "Para guardar el formulario en blanco debe <b>ingresar únicamente la fecha y las observaciones</b>"));
        }
        else {
            foreach($preguntas as $preg) {
                $this->form_validation->set_rules("preg_{$preg->id}[]", "", "required");
                $this->form_validation->set_rules("otro_{$preg->id}");
            }            

            if($this->form_validation->run()) {
                $postcosecha = $this->guardarObjPostcosecha($ruat_id);
                foreach($preguntas as $preg) {
                    $res_ids = $this->input->post("preg_{$preg->id}");
                    foreach($res_ids as $res_id) {
                        $otro = $this->input->post("otro_{$preg->id}") ?: null;
                        PostcosechaRespuesta::create(array(
                            'postcosecha_id'   => $postcosecha->id,
                            'pregunta_id'  => $preg->id,
                            'opcion_id'    => $res_id,
                            'otro'         => $otro  ));
                    }
                }
                $this->redireccionar();
            }
            else if(validation_errors())
                $this->twiggy->set('notif',array('type'=>'error', 
                    'text'=> "Debe responder todas las preguntas. <br> Revise los recuadros rojos"));
        }        

        $bloques = array(
            0 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3)),
            1 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            2 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3), array('inicio'=>4, 'fin'=>4)),
            3 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3), array('inicio'=>4, 'fin'=>5)),
            4 => array(array('inicio' => 0, 'fin' => 0), array('inicio'=>1, 'fin'=>1), array('inicio'=>2, 'fin'=>2)),
            5 => array(array('inicio' => 0, 'fin' => 0), array('inicio'=>1, 'fin'=>1)),
            6 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>3), array('inicio'=>4, 'fin'=>4)),
            7 => array(array('inicio' => 0, 'fin' => 0), array('inicio'=>1, 'fin'=>1)),
            8 => array(array('inicio' => 0, 'fin' => 0), array('inicio'=>1, 'fin'=>1)),
            9 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>4)),
           10 => array(array('inicio' => 0, 'fin' => 0), array('inicio'=>1, 'fin'=>1)),
           11 => array(array('inicio' => 0, 'fin' => 0)),
           12 => array(array('inicio' => 0, 'fin' => 1), array('inicio' => 2, 'fin' => 3), array('inicio' => 4, 'fin' => 6)),

            /*
            7 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            */
        );

        $productor = Productor::find_by_id(Ruat::find($ruat_id)->productor_id, array('include' => array('tipo_documento')));
        $respuestas_bd = array();       
        $postcosecha = Postcosecha::find_by_ruat_id($ruat_id);
        if($postcosecha) { //ya existe, cargar datos previamente guardados
            $resps = PostcosechaRespuesta::find_all_by_postcosecha_id($postcosecha->id);
            $otro = array();
            foreach($resps as $r)
                if($r->otro) $otro[$r->pregunta_id] = $r->otro;
            $this->twiggy->set('otro', $otro);
            
            $respuestas_bd = extract_prop($resps, 'opcion_id');
            $observaciones = $postcosecha->observaciones;

            $this->twiggy->set('soloLectura', $postcosecha->soloLectura($this));
        }
        else {
            $postcosecha = array();
        }

        $ruat = Ruat::find($ruat_id);
        $this->twiggy->set('ruat', $ruat);
        $this->twiggy->set("preguntas", $preguntas);
        $this->twiggy->set("bloques", $bloques);
        $this->twiggy->set("respuestas_bd", $respuestas_bd);
        $this->twiggy->set("postcosecha", $postcosecha);
        $this->twiggy->template("lepostcosecha/lepostcosecha");
        $this->twiggy->display();
    }
}