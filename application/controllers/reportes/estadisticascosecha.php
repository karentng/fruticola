<?php

class EstadisticasCosecha extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }
    
    public static function to_array($model){
        return $model->to_array();
    }

    public function index() {

        $renglones = array_map(array('EstadisticasCosecha', 'to_array'), RenglonProductivo::sorted());
        $municipios = array_map(array('EstadisticasCosecha', 'to_array'), Municipio::find_all_by_departamento_id(30, array('select' => 'id,nombre', 'order' => 'nombre')));
        $pregunta = array_map(array('EstadisticasCosecha', 'to_array'), CosechaPregunta::sorted());


        $this->twiggy->set('renglones', $renglones);
        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('preguntas', $pregunta);

        $this->twiggy->template("reportes/estadisticascosecha");
        $this->twiggy->display();
    }

    

}
