<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BPA extends CI_Controller {

    public function index()
    {
        //check_profile($this,"Administrador");

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');

        $data = array();
        $preguntasB = BpaPregunta::sortedB();
        $preguntasC = BpaPregunta::sortedC();
        $this->form_validation->set_rules('conclusionB', 'Conclusión', 'required');

        if ($this->form_validation->run()) {
            $bpa = new BuenasPracticas();
            $bpa->ruat_id = 1;
            $bpa->fecha = $this->input->post('fecha');
            $bpa->conclusion = $this->input->post('conclusionB');
            $bpa->nivel_bpa = $this->input->post('valorFinal');
            //var_dump($bpa);
            $bpa->save();
echo "pase por aqui";
            //for($i = 0;$i < count($preguntasC);$i++){

            //}
            //$this->input->post('' . $i);
        }else {
    echo validation_errors();
    
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