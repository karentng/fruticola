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

        $query = "SELECT avg(:var)
                    FROM productor
                    INNER JOIN economia ON productor_id=productor.id
        			";

		$frecuencias_sexo = Productor::connection()->query($query);

    	if(check_profile(array("Administrador","Coordinador"), false)){
            $this->twiggy->set('soloLectura', false);
        }else{
            $this->twiggy->set('soloLectura', true);
        }

        $this->twiggy->set('sexos', $frecuencias_sexo);
        $this->twiggy->template("reportes/perfilproductor");
        $this->twiggy->display();
    }
}