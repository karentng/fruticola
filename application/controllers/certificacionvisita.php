<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Certificacionvisita extends CI_Controller {

    public function index($id = null)
    {
        /*$preguntas = TPCPregunta::all(array('order' => 'categoria, ordenamiento'));
        
        $preguntas_ingresos = $preguntas_egresos = $preguntas_activos = $preguntas_totales = array();
        
        foreach ($preguntas as $obj) {
            if($obj->categoria === 'A')
                $preguntas_activos[] = $obj->to_array();
            elseif($obj->categoria === 'B')
                $preguntas_ingresos[] = $obj->to_array();
            elseif($obj->categoria === 'C')
                $preguntas_egresos[] = $obj->to_array();
            elseif($obj->categoria === 'D')
                $preguntas_totales[] = $obj->to_array();
        }*/
        
        $this->twiggy->register_function('var_dump');
        
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('respuestas', $respuestas);

        $this->twiggy->template("certificacionvisita/cetificacion_visita");
        $this->twiggy->display();
    }
}
