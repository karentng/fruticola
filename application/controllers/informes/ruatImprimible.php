<?php

class RuatImprimible extends CI_Controller {
    public function index() 
    {
        sleep(2); //simular tiempo de carga
        $this->twiggy->template("informes/ruatImprimible");
        $this->twiggy->display();
    }
}