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

    public function login()
    {
        $this->twiggy->template("manual/login");
        $this->twiggy->display();
    }

    public function listadoinicial()
    {
        $this->twiggy->template("manual/listadoinicial");
        $this->twiggy->display();
    }
    
    public function ruata()
    {
        $this->twiggy->template("manual/ruata");
        $this->twiggy->display();
    }

    public function ruatb(){
        $this->twiggy->template("manual/ruat-b");
        $this->twiggy->display();
    }

    public function ruatc(){
        $this->twiggy->template("manual/ruatc");
        $this->twiggy->display();
    }

    public function ruatd(){
        $this->twiggy->template("manual/ruatd");
        $this->twiggy->display();
    }

    public function ubicacion(){
        $this->twiggy->template("manual/ubicacion");
        $this->twiggy->display();
    }


    public function suelos(){
        $this->twiggy->template("manual/suelos");
        $this->twiggy->display();
    }


    public function bpa(){
        $this->twiggy->template("manual/bpa");
        $this->twiggy->display();
    }

    public function comparativo(){
        $this->twiggy->template("manual/comparativomunicipio");
        $this->twiggy->display();
    }

    public function vtp(){
        $this->twiggy->template("manual/vtp");
        $this->twiggy->display();
    }

    public function indicadores(){
        $this->twiggy->template("manual/indicadoresProduccion");
        $this->twiggy->display();
    }

    public function estilosaprendizaje(){
        $this->twiggy->template("manual/reporteEstilosAprendizaje");
        $this->twiggy->display();
    }


    public function cosecha(){
        $this->twiggy->template("manual/cosecha");
        $this->twiggy->display();
    }

    public function perfilproductor(){
        $this->twiggy->template("manual/perfilproductor");
        $this->twiggy->display();
    }
}
