<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatA extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function index()
    {
        check_profile($this, 'Administrador');
        
        $this->load->library('form_validation');
        //poner validaciones aqui. Ejemplo:
        //$this->form_validation->set_rules('name','Nombre', 'required|max_length[50]');

        if($this->form_validation->run())
        {
            //las validaciones pasaron, aqui iria la logica de insertar en la BD...
        }


        $this->twiggy->template("ruat/datos_personales");
        $this->twiggy->display();
    }
}