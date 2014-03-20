<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ManualUsuario extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        //check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
    }

    public function index()
    {
        $this->twiggy->template("manual/andres");
        $this->twiggy->display();
    }
}
