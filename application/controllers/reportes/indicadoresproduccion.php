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

        $this->twiggy->template("reportes/indicadoresproduccion");
        $this->twiggy->display();
    }

}
