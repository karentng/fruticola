<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RuatD extends CI_Controller {

    public function index() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('observacion', 'Observación', 'required');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');

        $ruat_id = 1; /// sacarlo de session o algo, pendiente definir
        
        ///Consulto la observacion de este RUAT (Edicion)
        $observacion = Observacion::first(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)
        ));

        ///si no existe observacion anterior, creo una nueva
        if (empty($observacion))
            $observacion = new Observacion;

        if ($this->form_validation->run()) {
            $observacion->ruat_id = $ruat_id;
            $observacion->observacion = $this->input->post('observacion');
            $observacion->save();
        }

        $this->twiggy->register_function('form_error');
        $this->twiggy->register_function('set_value');
        $this->twiggy->set('url_ruatc', site_url('ruatc'));
        $this->twiggy->set('observacion', $observacion);
        $this->twiggy->template("ruat/observaciones");
        $this->twiggy->display();
    }

}

