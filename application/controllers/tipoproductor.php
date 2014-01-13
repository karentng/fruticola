<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TipoProductor extends CI_Controller {

    public function index($id = null) {
        $id = 1;

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');


        ///consulo las respuestas B
        $respuesta_b = TPBRespuesta::first(array(
                    'conditions' => array('visita_id = ?', $id)
        ));
        $this->form_validation->set_rules("area_predio", ' ', 'required|numeric');
        $this->form_validation->set_rules("valor_uaf", ' ', 'required|numeric');
        $this->form_validation->set_rules("tipo_productor_uaf", ' ', 'required|is_natural');
        $this->form_validation->set_rules("clasificacion_productor_uaf", ' ', 'required|is_natural');


        ///consulto las preguntas C para cargarlas dinamicamente
        $preguntas_c = TPCPregunta::all(array('order' => 'categoria, ordenamiento'));

        ///consulo las respuestas C
        $respuestas_c = TPCRespuesta::all(array(
                    'conditions' => array('visita_id = ?', $id)
        ));
        ///acomodo las respuestas con la pregunta como llave
        $respuestas_c_aux = array();
        foreach ($respuestas_c as $obj)
            $respuestas_c_aux[$obj->pregunta_c_id] = $obj;

        ///acomodo las preguntas C por categoria
        $preguntas_ingresos = $preguntas_egresos = $preguntas_activos = $preguntas_totales = array();
        foreach ($preguntas_c as $obj) {

            $this->form_validation->set_rules("pregunta_c_{$obj->id}", ' ', 'required|numeric');
            $objAux = $obj->to_array();

            ///Si la pregunta C tiene respuesta, la agrego al objeto de la pregunta
            if (isset($respuestas_c_aux[$obj->id]))
                $objAux['respuesta_bd'] = $respuestas_c_aux[$obj->id]->valor;

            if ($obj->categoria === 'A')
                $preguntas_activos[] = $objAux;
            elseif ($obj->categoria === 'B')
                $preguntas_ingresos[] = $objAux;
            elseif ($obj->categoria === 'C')
                $preguntas_egresos[] = $objAux;
            elseif ($obj->categoria === 'D')
                $preguntas_totales[] = $objAux;
        }
        
        
        ///consulo las respuestas D
        $respuesta_d = TPDRespuesta::first(array(
                    'conditions' => array('visita_id = ?', $id)
        ));
        $this->form_validation->set_rules("criterio1", ' ', 'required|is_natural');
        $this->form_validation->set_rules("criterio2", ' ', 'required|is_natural');
        $this->form_validation->set_rules("criterio3", ' ', 'required|is_natural');
        $this->form_validation->set_rules("criterio4", ' ', 'required|is_natural');
        
        
        
        ///Si las validaciones son correctas procedo a guardar
        if ($this->form_validation->run()) {

            ///GUARDANDO RESPUESTAS B
            $respuesta_b = ($respuesta_b) ? $respuesta_b : new TPBRespuesta;
            $respuesta_b->visita_id = $id;
            $respuesta_b->area_predio =$this->input->post('area_predio');
            $respuesta_b->valor_uaf =$this->input->post('valor_uaf');
            $respuesta_b->tipo_productor_uaf =$this->input->post('tipo_productor_uaf');
            $respuesta_b->clasificacion_productor_uaf =$this->input->post('clasificacion_productor_uaf');
            $respuesta_b->save();
            
            
            ///GUARDANDO RESPUESTAS D
            $respuesta_d = ($respuesta_d) ? $respuesta_d : new TPDRespuesta;
            $respuesta_d->visita_id = $id;
            $respuesta_d->criterio1 =$this->input->post('criterio1');
            $respuesta_d->criterio2 =$this->input->post('criterio2');
            $respuesta_d->criterio3 =$this->input->post('criterio3');
            $respuesta_d->criterio4 =$this->input->post('criterio4');
            $respuesta_d->save();


            ///GUARDANDO RESPUESTAS C
            foreach ($preguntas_c as $obj) {

                ///Valido si la respuesta viene por post
                if (!empty($this->input->post('pregunta_c_' . $obj->id))) {

                    $valor = $this->input->post('pregunta_c_' . $obj->id);
                    ///si estoy editando o nueva respuesta
                    $objTPCRespuesta = isset($respuestas_c_aux[$obj->id]) ? $respuestas_c_aux[$obj->id] : new TPCRespuesta;
                    $objTPCRespuesta->visita_id = $id;
                    $objTPCRespuesta->pregunta_c_id = $obj->id;
                    $objTPCRespuesta->valor = $valor;
                    $objTPCRespuesta->save();
                }
            }
        }

        $this->twiggy->set('respuesta_b', $respuesta_b);
        $this->twiggy->set('preguntas_ingresos', $preguntas_ingresos);
        $this->twiggy->set('preguntas_egresos', $preguntas_egresos);
        $this->twiggy->set('preguntas_activos', $preguntas_activos);
        $this->twiggy->set('preguntas_totales', $preguntas_totales);
        $this->twiggy->set('respuesta_d', $respuesta_d);

        $this->twiggy->template("tipoproductor/tipo_productor");
        $this->twiggy->display();
    }

}
