<?php

class DiagnostiCosecha extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        check_profile($this,"Administrador","Coordinador", "Digitador");
    }

    public function index($ruat_id=NULL)
    {
        if(!$ruat_id) die("Acceso Invalido");

        $preguntas = CosechaPregunta::all(array(order=>'numero', 'include'=> 'opciones_respuesta'));
        //var_dump($preguntas);
        /*foreach($preguntas as $preg) {
            echo "<b>".$preg->texto."</b><br/>";
            foreach($preg->opciones_respuesta as $op) {
                echo $op->texto . "<br/>";
            }
        }*/

        $bloques = array(
            0 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>2)),
            1 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
            2 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7), array('inicio'=>8, 'fin'=>10)),
            3 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7), array('inicio'=>8, 'fin'=>10)),
            4 => array(array('inicio' => 0, 'fin' => 1), array('inicio'=>2, 'fin'=>2)),
            5 => array(array('inicio' => 0, 'fin' => 1)),
            6 => array(array('inicio' => 0, 'fin' => 3), array('inicio'=>4, 'fin'=>7)),
            7 => array(array('inicio' => 0, 'fin' => 2), array('inicio'=>3, 'fin'=>5)),
        );


        $this->twiggy->set("bloques", $bloques);
        $this->twiggy->set("preguntas", $preguntas);
        $this->twiggy->template("diagnosticosecha/index");
        $this->twiggy->display();
    }
}