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
    
    public function index1(){
        $this->twiggy->template("manual/eduar");
        $this->twiggy->display();
    }

    public function index2(){
        $this->twiggy->template("manual/ruat-b");
        $this->twiggy->display();
    }

    public function index3(){
        $this->twiggy->template("manual/cosecha");
        $this->twiggy->display();
    }

    public function index4(){
        $this->twiggy->template("manual/perfilproductor");
        $this->twiggy->display();
    }
}
