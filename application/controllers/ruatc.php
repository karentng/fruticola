<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RuatC extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        check_profile($this, "Administrador", "Coordinador", "Digitador");
    }

    public function index($ruat_id) {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');

        $preguntas = TipoPregunta::sorted();
        $respuestas = TipoRespuesta::sorted();

        //$ruat_id = 1; /// sacarlo de session o algo, pendiente definir

        ///consulto los datos de este RUAT
        $aprendizajeRespuestas_aux = AprendizajeRespuesta::all(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)
        ));
        $aprendizajeRespuestas = $aprendizajeRespuestas_models = array();
        foreach ($aprendizajeRespuestas_aux as $obj) {
            $aprendizajeRespuestas['radio_pregunta' . $obj->pregunta_id] = $obj->pregunta_id . '_' . $obj->respuesta_id;
            $aprendizajeRespuestas_models['radio_pregunta' . $obj->pregunta_id] = $obj;
        }

        for ($i = 1; $i < count($preguntas) + 1; $i++) {
            $this->form_validation->set_rules('radio_pregunta' . $i, ' ', 'required');
        }

        if ($this->form_validation->run()) {
            try {
                for ($i = 1; $i < count($preguntas) + 1; $i++) {

                    $tmpRespuesta = explode("_", $this->input->post('radio_pregunta' . $i));
                    
                    $respuestaRuat = isset($aprendizajeRespuestas_models['radio_pregunta' . $i]) ? $aprendizajeRespuestas_models['radio_pregunta' . $i] : new AprendizajeRespuesta;
                    $respuestaRuat->ruat_id = $ruat_id;
                    $respuestaRuat->respuesta_id = $tmpRespuesta[1];
                    $respuestaRuat->pregunta_id = $tmpRespuesta[0];
                    $respuestaRuat->save();
                }
            } catch (Exception $e) {
                echo( $e );
            }

            redirect(site_url("ruatd/index/$ruat_id"));
        }

        $this->twiggy->register_function('form_error');
        $this->twiggy->register_function('set_radio');
        
        $this->twiggy->set('aprendizajeRespuestas', $aprendizajeRespuestas);
        $this->twiggy->set('url_ruatb', site_url("ruatb/index/$ruat_id"));
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('respuestas', $respuestas);
        
        $this->twiggy->set("breadcrumbs", ruat_breadcrumbs(3, $ruat_id));
        $this->twiggy->template("ruat/apropiacion_aprendizajes");
        $this->twiggy->display();
    }

}

