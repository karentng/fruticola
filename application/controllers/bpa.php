<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bpa extends CI_Controller {

    public function index()
    {
        //check_profile($this,"Administrador");
         function to_array($model) { return $model->to_array(); }

        $data = array();
        $data['tiposBpaPreguntaB']     = array_map('to_array',BpaPregunta::sortedB());
        $data['tiposBpaPreguntaC']     = array_map('to_array',BpaPregunta::sortedC());
        //var_dump($data);
        $this->twiggy->set($data, NULL);
        $this->twiggy->set('combos', json_encode($data));
        //$this->twiggy->set('combos', $combos);
        $this->twiggy->template("ruat/bpa");
        $this->twiggy->display();
    }

    public function guardar() //si viene como parametro: $ruat_id
    {
        $input = json_decode(file_get_contents("php://input"));
        echo "me llego ";
        var_dump($input);

        //$finca = Finca::find_by_ruat_id($ruat_id);
        //if(!$finca) 
        
    }
}