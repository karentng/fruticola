<?php

class Portada extends CI_Controller {
    public function index() {
        if(current_user()) redirect("listadoruats");
        else {
            $this->twiggy->template("portada/portada");
            $this->twiggy->display();
        }
    }

    
}