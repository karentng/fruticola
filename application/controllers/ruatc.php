<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RuatC extends CI_Controller {

    public function index() {
        $this->load->library('form_validation');
        $preguntas = TipoPregunta::sorted();
        $respuestas = TipoRespuesta::sorted();

        for ($i = 1; $i < count($preguntas)+1; $i++) {
            $this->form_validation->set_rules('radio_pregunta' . $i, 'Pregunta' . $i, 'required');
        }        

        if ($this->form_validation->run()) {
            for ($i = 1; $i < count($preguntas) + 1; $i++) {

                try {
                    $tmpRespuesta = explode("_", $this->input->post('radio_pregunta' . $i));
                    $respuestaRuat = new AprendizajeRespuesta;
                    $respuestaRuat->ruat_id = 1;
                    $respuestaRuat->respuesta_id = $tmpRespuesta[1];
                    $respuestaRuat->pregunta_id = $tmpRespuesta[0];
                    $respuestaRuat->save();
                } catch (Exception $e) {
                    echo( $e );
                }
            } 
        }
        /* elsearray(14) { ["radio_pregunta1"]=> string(3) "1_2" ["radio_pregunta2"]=> string(3) "2_2" ["radio_pregunta3"]=> string(3) "3_2" ["radio_pregunta4"]=> string(3) "4_2" ["radio_pregunta5"]=> string(3) "5_2" ["radio_pregunta6"]=> string(3) "6_2" ["radio_pregunta7"]=> string(3) "7_2" ["radio_pregunta8"]=> string(3) "8_2" ["radio_pregunta9"]=> string(3) "9_2" ["radio_pregunta10"]=> string(4) "10_2" ["radio_pregunta11"]=> string(4) "11_2" ["radio_pregunta12"]=> string(4) "12_2" ["radio_pregunta13"]=> string(4) "13_2" ["radio_pregunta14"]=> string(4) "14_2" } 
          {
          echo('campos sin llenar'); die;
          } */
        
        $this->twiggy->register_function('set_radio');
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('post', $this->input->post());
        $this->twiggy->set('respuestas', $respuestas);
        $this->twiggy->template("ruat/apropiacion_aprendizajes");
        $this->twiggy->display();
    }

}

