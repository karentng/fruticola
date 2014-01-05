<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RuatD extends CI_Controller {

    public function index($ruat_id = null) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('observacion', 'Observación', 'required');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');

        $ruat_id = 1; /// sacarlo de session o algo, pendiente definir
        ///Consulto la observacion de este RUAT (Edicion)
        $observacion = Observacion::first(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)
        ));
        
        $objRuat = Ruat::find($ruat_id, array('joins' => array('productor')));

        ///si no existe observacion anterior, creo una nueva
        if (empty($observacion))
            $observacion = new Observacion;

        $upload_result = '';
        if ($this->form_validation->run()) {
            $observacion->ruat_id = $ruat_id;
            $observacion->observacion = $this->input->post('observacion');
            
            $arr_upload_result = $this->do_upload($ruat_id);
            if(!isset($arr_upload_result['error']) && isset($arr_upload_result['upload_data']))
                $observacion->ruta_formulario = 'ruat/'.$arr_upload_result['upload_data']['file_name'];
            else
                $upload_result = $arr_upload_result['error'];                
            
            $observacion->save();
        }

        $this->twiggy->register_function('form_open_multipart');
        $this->twiggy->register_function('form_error');
        $this->twiggy->register_function('set_value');
        $this->twiggy->register_function('base_url');
        $this->twiggy->set('url_ruatc', site_url('ruatc'));
        $this->twiggy->set('observacion', $observacion);
        $this->twiggy->set('upload_result', $upload_result);
        $this->twiggy->set('objRuat', $objRuat);
        $this->twiggy->template("ruat/observaciones");
        $this->twiggy->display();
    }

    private function do_upload($ruat_id) {
        $config['upload_path'] = './uploads/ruat';
        $config['allowed_types'] = 'pdf|png';
        $config['max_size'] = '10240';/// 10MiB
        $config['overwrite'] = true;/// 10MiB
        $config['file_name'] = $ruat_id;/// 10MiB

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('archivo_formulario')) {
            return array('error' => $this->upload->display_errors('<div><label class="error">', '</label></div>'));
        } else {
            return array('upload_data' => $this->upload->data());
        }
    }

}

