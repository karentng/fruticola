<?php

class Uploads extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in()) show_404();        
        $this->load->helper('download');
    }

    public function ruat($file)
    {
        $filepath = FCPATH."uploads/ruat/$file";
        force_download($file, file_get_contents($filepath));
    }

    public function documentos_identificacion($file)
    {
        $filepath = FCPATH."uploads/documentos_identificacion/$file";
        force_download($file, file_get_contents($filepath));
    }

    public function bpa($file)
    {
        $filepath = FCPATH."uploads/bpa/$file";
        force_download($file, file_get_contents($filepath));
    }    

     public function vtp($file)
    {
        $filepath = FCPATH."uploads/vtp/$file";
        force_download($file, file_get_contents($filepath));
    }    
}