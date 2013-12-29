<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RuatC extends CI_Controller {

    public function index() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');
        
        $preguntas = TipoPregunta::sorted();
        $respuestas = TipoRespuesta::sorted();

        for ($i = 1; $i < count($preguntas)+1; $i++) {
            $this->form_validation->set_rules('radio_pregunta' . $i, ' ', 'required');
        }        

        if ($this->form_validation->run()) {
            try {
                for ($i = 1; $i < count($preguntas) + 1; $i++) {


                        $tmpRespuesta = explode("_", $this->input->post('radio_pregunta' . $i));
                        $respuestaRuat = new AprendizajeRespuesta;
                        $respuestaRuat->ruat_id = 1;
                        $respuestaRuat->respuesta_id = $tmpRespuesta[1];
                        $respuestaRuat->pregunta_id = $tmpRespuesta[0];
                        $respuestaRuat->save();

                } 
            } catch (Exception $e) {
                echo( $e );
            }
            
            redirect(site_url('ruatd'));
        }
        
        
        $this->twiggy->register_function('form_error');
        $this->twiggy->register_function('set_radio');
        $this->twiggy->set('url_ruatb', site_url('ruatb'));
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('respuestas', $respuestas);
        $this->twiggy->template("ruat/apropiacion_aprendizajes");
        $this->twiggy->display();
    }

}

