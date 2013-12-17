<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatC extends CI_Controller {

    public function index()
    {
        $this->load->library('form_validation');
        //poner validaciones aqui. Ejemplo:
        //$this->form_validation->set_rules('name','Nombre', 'required|max_length[50]');

        if($this->form_validation->run())
        {
            //las validaciones pasaron, aqui iria la logica de insertar en la BD...
        }

        $this->twiggy->template("ruat/apropiacion_aprendizajes");
        $this->twiggy->display();
    }
}