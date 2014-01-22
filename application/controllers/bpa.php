<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BPA extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
    }

    public function index($ruat_id)
    {
        //var_dump($_FILES);
        //    die();

        $this->load->library('form_validation');

        $data = array();
        $preguntasB = BpaPregunta::sortedB();
        $preguntasC = BpaPregunta::sortedC();

        $this->form_validation->set_rules('conclusionB', 'Conclusión');
        $this->form_validation->set_rules('recomendacionFinal', 'Recomendación', 'required');
        $this->form_validation->set_rules('fecha', 'Fecha', 'required');


        $datosBPA = BuenasPracticas::find_by_ruat_id($ruat_id);
        $existePreviamente = false;
        if($datosBPA) {
            $datosBPA = $datosBPA;
            $existePreviamente = true;
            $arreglo = $datosBPA->to_array();
            $arreglo['fecha'] = $datosBPA->fecha_visita->format("Y-m-d");
            $this->twiggy->set("datosBPA", $arreglo);

            $this->twiggy->set('soloLectura', $datosBPA->soloLectura($this));
        }
        else {
            //$datosBPA = array('fecha' => date('Y-m-d'), 'conclusion' => '', 'recomendacion' => '', 'nivel_bpa' => 0);
            $datosBPA = array('fecha' => '', 'conclusion' => '', 'recomendacion' => '', 'nivel_bpa' => 0);
            $this->twiggy->set("datosBPA", $datosBPA);
        }

        

        //$preguntasB = BpaPregunta::find_all_by_seccion('B');
        foreach($preguntasB as $preg) {
            $this->form_validation->set_rules("recomendacion".$preg->id);
            $this->form_validation->set_rules("sino".$preg->id);
        }
        $this->form_validation->set_rules("excepcion42");
        //$preguntasC = BpaPregunta::find_all_by_seccion('C');
        foreach($preguntasC as $preg) {
            
            if(!($this->input->post('excepcion42')=='on')){
                if($preg->id >= 26 && $preg->id <= 30){
                    $this->form_validation->set_rules("observacion".$preg->id);
                    $this->form_validation->set_rules("valor".$preg->id);
                }else{
                    $this->form_validation->set_rules("observacion".$preg->id, 'Recomendación requerida', 'required');
                    $this->form_validation->set_rules("valor".$preg->id, 'Recomendación requerida', 'required');
                }
            }else{
                $this->form_validation->set_rules("observacion".$preg->id, 'Recomendación requerida', 'required');
                $this->form_validation->set_rules("valor".$preg->id, 'Recomendación requerida', 'required');
            }
        }



        if ($this->form_validation->run()) {
            //$bpa = $bpa_id ? BuenasPracticas::find($bpa_id) : new BuenasPracticas();
            
            if($existePreviamente){// existe
                //var_dump($datosBPA);
                $bpa = $datosBPA;
            }else{
                $bpa = new BuenasPracticas();
                $bpa->ruat_id = $ruat_id;
                $bpa->creador_id = current_user('id');
            }

            $bpa->fecha_visita = $this->input->post('fecha');
            $bpa->conclusion = $this->input->post('conclusionB');
            $bpa->nivel_bpa = $this->input->post('valorFinal');
            $bpa->recomendacion = $this->input->post('recomendacionFinal');
            $bpa->save();


            ///SUBO EL ARCHIVO (revisar)
            if(isset($_FILES["archivo_formulario"]) && !empty($_FILES["archivo_formulario"]["name"])) {
                $arr_upload_result = $this->do_upload($bpa->id);
                if(!isset($arr_upload_result['error']) && isset($arr_upload_result['upload_data'])){
                    $bpa->archivo_fisico = 'bpa/'.$arr_upload_result['upload_data']['file_name'];
                    $bpa->save();
                }else{
                    $upload_result = $arr_upload_result['error']; 
                    $this->twiggy->set("notif", array('type'=>'error', 'text' => $upload_result));
                    $this->twiggy->set("upload_result", $upload_result);
                    //die($upload_result);
                }
            }

            $conteoB = count($preguntasB);
            $conteoC = count($preguntasC);
            $conteo = $conteoB + $conteoC;

            $cont = 0;

            $idsB = array();
            foreach($preguntasB as $pregunta){
                array_push($idsB, $pregunta->id);
            }

            $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpa->id, $idsB), 'order' => 'id'));
            for($i = 1;$i<=$conteo;$i++){
                if($this->input->post('sino'.$i) === FALSE && $this->input->post('recomendacion'.$i) === FALSE){
                    continue;
                }

                $bpaR;
                if(count($respuestasB)>0){
                    $bpaR = $respuestasB[$cont];
                    $cont++;
                    //echo "paso";
                }else{
                    $bpaR = new BpaRespuesta();
                    $bpaR->bpa_id = $bpa->id;
                }
                
                $bpaR->pregunta_id = $i;
                if($this->input->post('sino'.$i) == 'on'){
                    $bpaR->puntaje = 1;
                }else{
                    $bpaR->puntaje = 0;
                }
                
                $bpaR->observacion = $this->input->post('recomendacion'.$i);

                $bpaR->save();
            }

            $cont = 0;
            $idsC = array();
            foreach($preguntasC as $pregunta){
                array_push($idsC, $pregunta->id);
            }

            $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpa->id, $idsC), 'order' => 'id'));

            for($i = 1;$i<=$conteo;$i++){
                if($this->input->post('valor'.$i) === FALSE){
                    continue;
                }

                $bpaR;
                if(count($respuestasC)>0){
                    $bpaR = $respuestasC[$cont];
                    $cont++;
                }else{
                    $bpaR = new BpaRespuesta();
                    $bpaR->bpa_id = $bpa->id;
                }
                $bpaR->pregunta_id = $i;
                $bpaR->puntaje = $this->input->post('valor'.$i);

                if(!($this->input->post('excepcion42')=='on')){
                    //var_dump($i);
                    if($i >= 26 && $i <= 30){ //26 -30
                        $bpaR->puntaje = 0;
                        $bpaR->observacion = "";
                    }else{
                        $bpaR->observacion = $this->input->post('observacion'.$i);
                        //$this->form_validation->set_rules("observacion".$preg->id, 'Recomendación requerida', 'required');    
                    }
                }else{
                    //$this->form_validation->set_rules("observacion".$preg->id, 'Recomendación requerida', 'required');
                    $bpaR->observacion = $this->input->post('observacion'.$i);
                }

                //$bpaR->observacion = $this->input->post('observacion'.$i);
                
                $bpaR->save();
            }
            
            if(empty($upload_result)) {
                $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'Formulario BPA guardado exitosamente'));
                redirect('listadoruats');
            }
        }else if(validation_errors()){
            $this->twiggy->set('notif',array('type'=>'error', 'text'=> "Formulario incompleto. <br> Revise los recuadros rojos"));
        }

        
        if($existePreviamente){
            $idsB = extract_prop($preguntasB, "id");
            $idsC = extract_prop($preguntasC, "id");
            $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsB), 'order' => 'id'));
            for($i = 0;$i<count($respuestasB);$i++){
                //$this->twiggy->set('sino'.$respuestasB[$i]->pregunta_id, $respuestasB[$i]->puntaje);
                //var_dump('sino'.$respuestasB[$i]->id);
                if($respuestasB[$i]->puntaje == 1){
                    $respuestasB[$i]->puntaje = true;
                }else{
                    $respuestasB[$i]->puntaje = false;
                }
            }
            
            $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsC), 'order' => 'id'));
            $this->twiggy->set('existe', 'yes');
            //$this->twiggy->set('datosBpa', $datosBPA);

            $condicionExcepcion = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id = ?', $datosBPA->id, 26), 'order' => 'id'));
            
            if($condicionExcepcion->observacion == '' && $condicionExcepcion->puntaje == 0){
                $this->twiggy->set('excepcion42', false);
            }else{
                $this->twiggy->set('excepcion42', true);
            }
            
            $this->twiggy->set('respuestasB', $respuestasB);
            
            $this->twiggy->set('respuestasC', $respuestasC);
        }else{
            $this->twiggy->set('existe', 'not');
            $this->twiggy->set('excepcion42', true);

        }

        $ruat = Ruat::find($ruat_id);
        $this->twiggy->set('ruat', $ruat); //necesario para el encabezado info_productor

        $this->twiggy->register_function('form_open_multipart');
        $this->twiggy->set('numForm', $ruatNumFormulario);
        $this->twiggy->set('preguntasB', $preguntasB);
        $this->twiggy->set('preguntasC', $preguntasC);
        $this->twiggy->set('tamaño', count($preguntasC)+count($preguntasB));

        
        
        $this->twiggy->template("bpa/bpa");
        $this->twiggy->display();
    }

    private function do_upload($ruat_id) {
        $config['upload_path'] = './uploads/bpa';
        $config['allowed_types'] = 'pdf|png';
        $config['max_size'] = '10240';/// 10MiB
        $config['overwrite'] = true;/// 10MiB
        $config['file_name'] = $ruat_id;/// 10MiB

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('archivo_formulario')) {
            return array('error' => $this->upload->display_errors('', ''));
        } else {
            return array('upload_data' => $this->upload->data());
        }
    }   
}