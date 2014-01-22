<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TipoProductor extends CI_Controller {

    public function __construct() {
        parent::__construct();

        check_profile($this, "Administrador", "Coordinador", "Digitador");
    }

    public function index($ruat_id = null) {
//        $ruat_id = 1;
        $user_id = current_user('id');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');


        ///Obtengo los datos del usuario en session
        $usuaioSesion = $this->ion_auth->user(Ruat::find($ruat_id)->creador_id)->row();

        ///Consulto la info de la visita
        $visitaTipoProductor = VisitaTipoProductor::first(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)
        ));
        $this->form_validation->set_rules("fecha", ' ', 'required');
        $this->form_validation->set_rules("credito_agricola", ' ', 'required');
        if ($this->input->post('credito_agricola') == 1)
            $this->form_validation->set_rules("observacion", ' ', 'required');


        $id = null;
        if ($visitaTipoProductor) {
            $this->twiggy->set('soloLectura', $visitaTipoProductor->soloLectura($this));
            $id = $visitaTipoProductor->id;
        }

        ///Consulto los datos del productor, a partir del ruat
        $ruat = Ruat::find($ruat_id, array('joins' => array('productor')));
        $productor = $ruat->productor;
        $finca = $ruat->finca;
        $municipio = $finca->municipio;
        $municipios_uaf = $municipio->municipios_uaf;

        ///consulto las preguntas C para cargarlas dinamicamente
        $preguntas_c = TPCPregunta::all(array('order' => 'categoria, ordenamiento'));

        ///consulo las respuestas C
        $respuestas_c = TPCRespuesta::all(array(
                    'conditions' => array('visita_id = ?', $id)
        ));
        ///acomodo las respuestas con la pregunta como llave
        $respuestas_c_aux = array();
        foreach ($respuestas_c as $obj)
            $respuestas_c_aux[$obj->pregunta_c_id] = $obj;

        ///acomodo las preguntas C por categoria
        $preguntas_ingresos = $preguntas_egresos = $preguntas_activos = $preguntas_totales = array();
        foreach ($preguntas_c as $obj) {

            $this->form_validation->set_rules("pregunta_c_{$obj->id}", ' ', 'required|numeric');
            $objAux = $obj->to_array();

            ///Si la pregunta C tiene respuesta, la agrego al objeto de la pregunta
            if (isset($respuestas_c_aux[$obj->id]))
                $objAux['respuesta_bd'] = $respuestas_c_aux[$obj->id]->valor;

            if ($obj->categoria === 'A')
                $preguntas_activos[] = $objAux;
            elseif ($obj->categoria === 'B')
                $preguntas_ingresos[] = $objAux;
            elseif ($obj->categoria === 'C')
                $preguntas_egresos[] = $objAux;
            elseif ($obj->categoria === 'D')
                $preguntas_totales[] = $objAux;
        }

        ///Si las validaciones son correctas procedo a guardar
        if ($this->form_validation->run()) {

            ///GUARDANDO LA VISITA
            $visitaTipoProductor = ($visitaTipoProductor) ? $visitaTipoProductor : new VisitaTipoProductor;

            $subirArchivo = true;
            ///SUBO EL ARCHIVO
            if (isset($_FILES["archivo_formulario"]) && !empty($_FILES["archivo_formulario"]["name"])) {
                $arr_upload_result = $this->do_upload($id);
                if (!isset($arr_upload_result['error']) && isset($arr_upload_result['upload_data']))
                    $visitaTipoProductor->archivo_fisico = 'vtp/' . $arr_upload_result['upload_data']['file_name'];
                else {
                    $subirArchivo = false;
                    $upload_result = $arr_upload_result['error'];
                }
            }
            
            if ($subirArchivo) {
                $visitaTipoProductor->ruat_id = $ruat_id;
                $visitaTipoProductor->fecha = $this->input->post('fecha');
                $visitaTipoProductor->observaciones = $this->input->post('observacion');
                $visitaTipoProductor->credito_agricola = $this->input->post('credito_agricola');
                $visitaTipoProductor->creador_id = $user_id;
                $visitaTipoProductor->save();

                ///obtengo el id de la visita luego de guardar/editar
                $id = $visitaTipoProductor->id;


                ///GUARDANDO RESPUESTAS C
                foreach ($preguntas_c as $obj) {

                    ///Valido si la respuesta viene por post
                    //if (!empty($this->input->post('pregunta_c_' . $obj->id))) {
                    if ($this->input->post('pregunta_c_' . $obj->id)) { // compatibilidad con version vieja de php. Es equivalente???...
                        $valor = $this->input->post('pregunta_c_' . $obj->id);
                        ///si estoy editando o nueva respuesta
                        $objTPCRespuesta = isset($respuestas_c_aux[$obj->id]) ? $respuestas_c_aux[$obj->id] : new TPCRespuesta;
                        $objTPCRespuesta->visita_id = $id;
                        $objTPCRespuesta->pregunta_c_id = $obj->id;
                        $objTPCRespuesta->valor = $valor;
                        $objTPCRespuesta->save();
                    }
                }

                $this->session->set_flashdata("notif", array('type' => 'success', 'text' => 'Formulario Tipo Productor guardado exitosamente'));
                redirect('listadoruats');
            }else{
                $this->twiggy->set('notif', array('type' => 'error', 'text' => "Se encontraron errores al procesar el formulario. <br> Revise los recuadros rojos"));
            }
        } else if (validation_errors()) {
            $this->twiggy->set('notif', array('type' => 'error', 'text' => "Se encontraron errores al procesar el formulario. <br> Revise los recuadros rojos"));
        }

        $this->twiggy->register_function('form_open_multipart');

        $this->twiggy->set('ruat', $ruat);
        $this->twiggy->set('municipio', $municipio);
        $this->twiggy->set('municipios_uaf', $municipios_uaf);
        $this->twiggy->set('numForm', $ruatNumFormulario);
        $this->twiggy->set('usuaioSesion', $usuaioSesion);
        $this->twiggy->set('productor', $productor);
        $this->twiggy->set('finca', $finca);
        $this->twiggy->set('visitaTipoProductor', $visitaTipoProductor);
//        $this->twiggy->set('respuesta_b', $respuesta_b);
        $this->twiggy->set('preguntas_ingresos', $preguntas_ingresos);
        $this->twiggy->set('preguntas_egresos', $preguntas_egresos);
        $this->twiggy->set('preguntas_activos', $preguntas_activos);
        $this->twiggy->set('preguntas_totales', $preguntas_totales);
//        $this->twiggy->set('respuesta_d', $respuesta_d);
        $this->twiggy->set('upload_result', $upload_result);

        $this->twiggy->template("tipoproductor/tipo_productor");
        $this->twiggy->display();
    }

    private function do_upload($id) {
        $config['upload_path'] = './uploads/vtp';
        $config['allowed_types'] = 'pdf|png';
        $config['max_size'] = '10240'; /// 10MiB
        $config['overwrite'] = true; /// 10MiB
        $config['file_name'] = $id; /// 10MiB

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('archivo_formulario')) {
            return array('error' => $this->upload->display_errors('<div><label class="error">', '</label></div>'));
        } else {
            return array('upload_data' => $this->upload->data());
        }
    }

}
