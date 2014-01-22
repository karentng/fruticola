<?php

class DiagnostiCosecha extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador", "Digitador", "Consultas"));
    }

    public function index($ruat_id=NULL)
    {
        if(!$ruat_id) die("Acceso Invalido");

        $preguntas = CosechaPregunta::all(array(order=>'numero', 'include'=> 'opciones_respuesta'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('fecha_visita', 'Fecha de Visita', 'required');
        foreach($preguntas as $preg) {
            $this->form_validation->set_rules("preg_{$preg->id}[]", "", "required");
            $this->form_validation->set_rules("otro_{$preg->id}");
        }
        

        if($this->form_validation->run()) {
            $cosecha = Cosecha::find_by_ruat_id($ruat_id);
            if(!$cosecha) {
                $cosecha = new Cosecha;
                $cosecha->ruat_id = $ruat_id;
                $cosecha->creador_id = current_user('id');
            }
            else {
                CosechaRespuesta::delete_all(array('conditions' => array('cosecha_id'=>$cosecha->id)));
            }

            $cosecha->observaciones = $this->input->post('observaciones');
            $cosecha->fecha_visita = $this->input->post('fecha_visita');
            $cosecha->save();

            foreach($preguntas as $preg) {
                $res_ids = $this->input->post("preg_{$preg->id}");

                foreach($res_ids as $res_id) {
                    $otro = $this->input->post("otro_{$preg->id}") ?: null;

                    CosechaRespuesta::create(array(
                        'cosecha_id'   => $cosecha->id,
                        'pregunta_id'  => $preg->id,
                        'opcion_id'    => $res_id,
                        'otro'         => $otro  ));
                }
            }
            $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'Formulario Cosecha guardado exitosamente'));
            redirect('listadoruats');
        }
        else if(validation_errors()) {
            $this->twiggy->set('notif',array('type'=>'error', 'text'=> "Debe responder todas las preguntas. <br> Revise los recuadros rojos"));
        }
        

        $bloques = array(
            0 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>2)),
            1 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            2 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7), array('inicio'=>8, 'fin'=>10)),
            3 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7), array('inicio'=>8, 'fin'=>10)),
            4 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>2)),
            5 => array(array('inicio' => 0, 'fin' => 1)),
            6 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7)),
            7 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
        );

        $productor = Productor::find_by_id(Ruat::find($ruat_id)->productor_id, array('include' => array('tipo_documento')));
        
        $respuestas_bd = array();
        
        $cosecha = Cosecha::find_by_ruat_id($ruat_id);
        if($cosecha) { //ya existe, cargar datos previamente guardados
            $resps = CosechaRespuesta::find_all_by_cosecha_id($cosecha->id);
            $otro = array();
            foreach($resps as $r)
                if($r->otro) $otro[$r->pregunta_id] = $r->otro;
            $this->twiggy->set('otro', $otro);
            
            $respuestas_bd = extract_prop($resps, 'opcion_id');
            $observaciones = $cosecha->observaciones;

            $this->twiggy->set('soloLectura', $cosecha->soloLectura($this));
        }
        else {
            $cosecha = array();
        }

        $ruat = Ruat::find($ruat_id);
        $this->twiggy->set('ruat', $ruat);

        $this->twiggy->set("preguntas", $preguntas);
        $this->twiggy->set("bloques", $bloques);
        //$this->twiggy->set("productor", $productor);
        $this->twiggy->set("respuestas_bd", $respuestas_bd);
        $this->twiggy->set("cosecha", $cosecha);
        $this->twiggy->template("diagnosticosecha/diagnosticosecha");
        $this->twiggy->display();
    }
}