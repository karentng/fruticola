<?php

class Prueba extends CI_Controller {
    public function index() 
    {
        $this->twiggy->template("informes/prueba");
        $this->twiggy->display();
    }
}