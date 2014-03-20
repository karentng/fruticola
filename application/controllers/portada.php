<?php

class Portada extends CI_Controller {
    public function index() {
        $this->twiggy->template("portada/portada");
        $this->twiggy->display();
    }

    
}