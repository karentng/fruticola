<?php

class Main extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        $informes = array(
            array('url' => 'informes/prueba', 'title' => 'Reportico', 'icon' => 'i-file-8', 'color' => 'green'),
            array('url' => 'informes/ruatImprimible', 'title' => 'Imprimir Ruat', 'icon' => 'i-file-8', 'color' => 'blue'),
            array('url' => 'informes/oto', 'title' => 'Oto Reporte', 'icon' => 'i-file-7', 'color' => 'red')

        );

        $this->twiggy->set('informes', $informes);
        $this->twiggy->template("informes/main");
        $this->twiggy->display();
    }
}