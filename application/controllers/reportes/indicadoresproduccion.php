<?php

class Indicadoresproduccion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index() {

        function to_array($model) {
            return $model->to_array();
        }
        
        $municipios = array_map('to_array',Municipio::find_all_by_departamento_id(30, array('select'=>'id,nombre' , 'order'=>'nombre')));
        $renglonesProductivos = array_map('to_array',RenglonProductivo::sorted());

        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('renglonesProductivos', $renglonesProductivos);
        
        $this->twiggy->template("reportes/indicadoresproduccion");
        $this->twiggy->display();
    }
    
    private function consultarDatos($municipio, $renglon){
        
    }

}
