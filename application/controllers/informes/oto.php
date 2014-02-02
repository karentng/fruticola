<?php

class Oto extends CI_Controller {
    public function index() 
    {
        sleep(2); //simular tiempo de carga
        $this->twiggy->template("informes/oto");
        $this->twiggy->display();
    }
}