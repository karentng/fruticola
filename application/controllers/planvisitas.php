<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PlanVisitas extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        
        check_profile(array("Administrador", "Coordinador", "Digitador","Consultas"));
    }

    public function index() {

        $respuestas_relacion_visitas = TipoActividadVisita::all(array('select' => 'id,descripcion', 'order' => 'orden', 'conditions' => array('categoria = ?', 1)));
        $respuestas_visitas = TipoActividadVisita::all(array('select' => 'id,descripcion', 'order' => 'orden', 'conditions' => array('categoria = ?', 2)));
        $respuestas_actividades = TipoActividadVisita::all(array('select' => 'id,descripcion', 'order' => 'orden', 'conditions' => array('categoria = ?', 3)));
        
        //var_dump($preguntasTipo1actividadVisita); die;

        $this->twiggy->set('usuaioSesion', $usuaRioSesion);
        $this->twiggy->set('respuestas_relacion_visitas', $respuestas_relacion_visitas);
        $this->twiggy->set('respuestas_visitas', $respuestas_visitas);
        $this->twiggy->set('respuestas_actividades', $respuestas_actividades);


        $this->twiggy->set('preguntas_relacion_visitas', $preguntas_relacion_visitas);
        $this->twiggy->set('preguntas_visitas', $preguntas_visitas);
        $this->twiggy->set('preguntas_actividades', $preguntas_actividades);

        $this->twiggy->template("informes/planvisitas");
        $this->twiggy->display();
    }

}
