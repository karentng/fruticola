<?php

class Main extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        $this->twiggy->template("reportes/main");
        $this->twiggy->display();
    }
}