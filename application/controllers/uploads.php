<?php

class Uploads extends CI_Controller {
    public function ruat($file)
    {
        if(!$this->ion_auth->logged_in()) show_404();

        $this->load->helper('download');
        $filepath = FCPATH."uploads/ruat/$file";
        force_download($file, file_get_contents($filepath));
    }

    public function documentos_identificacion($file)
    {
        if(!$this->ion_auth->logged_in()) show_404();

        $this->load->helper('download');
        $filepath = FCPATH."uploads/documentos_identificacion/$file";
        force_download($file, file_get_contents($filepath));
    }
}