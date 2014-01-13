<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tp extends CI_Controller {

    public function index($id = null)
    {
        $id = 1;
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');
        
        ///consulto las preguntas para cargarlas dinamicamente
        $preguntas_c= TPCPregunta::all(array('order' => 'categoria, ordenamiento'));
        
        ///consulo las respuestas
        $respuestas_c = TPCRespuesta::all(array(
                'conditions' => array('visita_id = ?', $id)
            ));
        ///acomodo las respuestas con la pregunta como llave
        $respuestas_c_aux = array();
        foreach ($respuestas_c as $obj)
            $respuestas_c_aux[$obj->pregunta_c_id] = $obj;
        
        
        ///acomodo las preguntas por categoria
        $preguntas_ingresos = $preguntas_egresos = $preguntas_activos = $preguntas_totales = array();        
        foreach ($preguntas_c as $obj) {
            
            $this->form_validation->set_rules("pregunta_c_{$obj->id}", ' ', 'required|numeric');            
            $objAux = $obj->to_array();
            
            ///Si la pregunta tiene respuesta, la agrego al objeto de la pregunta
            if(isset($respuestas_c_aux[$obj->id]))
                $objAux['respuesta_bd'] = $respuestas_c_aux[$obj->id]->valor;
            
            if($obj->categoria === 'A')
                $preguntas_activos[] = $objAux;
            elseif($obj->categoria === 'B')
                $preguntas_ingresos[] = $objAux;
            elseif($obj->categoria === 'C')
                $preguntas_egresos[] = $objAux;
            elseif($obj->categoria === 'D')
                $preguntas_totales[] = $objAux;
        }
        
        if ($this->form_validation->run()) {
           
            foreach ($preguntas_c as $obj) {
                
//                var_dump($this->input->post('pregunta_c_' . $obj->id));
                ///Valido si la respuesta viene por post
                if(empty($this->input->post('pregunta_c_' . $obj->id)))
                    continue;
                
                
                $valor = $this->input->post('pregunta_c_' . $obj->id);
                ///si estoy editando o nueva respuesta
                $objTPCRespuesta = isset($respuestas_c_aux[$obj->id]) ? $respuestas_c_aux[$obj->id] : new TPCRespuesta;
                $objTPCRespuesta->visita_id = $id;
                $objTPCRespuesta->pregunta_c_id = $obj->id;
                $objTPCRespuesta->valor = $valor;
                $objTPCRespuesta->save();
            }
            
//            die();
            
            
        }
        
        $this->twiggy->set('preguntas_ingresos', $preguntas_ingresos);
        $this->twiggy->set('preguntas_egresos', $preguntas_egresos);
        $this->twiggy->set('preguntas_activos', $preguntas_activos);
        $this->twiggy->set('preguntas_totales', $preguntas_totales);
        $this->twiggy->template("vtp/tp");
        $this->twiggy->display();
    }
}
