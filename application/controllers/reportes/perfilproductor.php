<?php

class Perfilproductor extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        $data = array();

        $query = "SELECT avg(A.edad)
                    FROM(
                    SELECT DISTINCT productor, edad
                    FROM perfil_productor
                    ORDER BY productor) as A
                    ";

        $promedio_edad = Perfilproductor::connection()->query($query);

        if(check_profile(array("Administrador","Coordinador"), false)){
            $this->twiggy->set('soloLectura', false);
        }else{
            $this->twiggy->set('soloLectura', true);
        }

        //$this->twiggy->set('promedio_edad', $promedio_edad);
        $this->twiggy->template("reportes/perfilproductor");
        $this->twiggy->display();
    }
}