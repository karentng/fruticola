<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RuatD extends CI_Controller {

    public function index() {
        $this->load->library('form_validation');
       
        $this->twiggy->template("ruat/observaciones");
        $this->twiggy->display();
    }

}

