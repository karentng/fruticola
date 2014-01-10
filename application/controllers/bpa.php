<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bpa extends CI_Controller {

    public function index()
    {
        //check_profile($this,"Administrador");

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');


        function to_array($model) { return $model->to_array(); }

        $data = array();
        $preguntasB = BpaPregunta::sortedB();
        $preguntasC = BpaPregunta::sortedC();


        if ($this->form_validation->run()) {
            for($i = 0;$i < count($preguntasC);$i++){

            }
            //$this->input->post('' . $i);
        }

        //var_dump($preguntasC);
        $this->twiggy->set('preguntasB', $preguntasB);
        $this->twiggy->set('preguntasC', $preguntasC);
        $this->twiggy->set('tamaño', count($preguntasC)+count($preguntasB));

        //$this->twiggy->set($data, NULL);
        //$this->twiggy->set('combos', json_encode($data));
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