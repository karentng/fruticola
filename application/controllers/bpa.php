<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BPA extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
    }

    public function index($ruat_id, $nro_visita=null)
    {
        if(!$ruat_id) show_404();

        if($nro_visita===null) {
            //seleccionar ultima BPA
            $ultimo_bpa = BuenasPracticas::find_by_ruat_id($ruat_id, array('order' => 'nro_visita DESC'));
            $nro_visita = $ultimo_bpa ? $ultimo_bpa->nro_visita : 0;
            redirect(array("bpa","index",$ruat_id,$nro_visita));
            return;
        }

        $this->load->library('form_validation');

        $data = array();
        $preguntasB = BpaPregunta::sortedB();
        $preguntasC = BpaPregunta::sortedC();

        $this->form_validation->set_rules('conclusionB', 'Conclusión');
        $this->form_validation->set_rules('recomendacionFinal', 'Recomendación', 'required');
        $this->form_validation->set_rules('fecha', 'Fecha', 'required');


        $datosBPA = BuenasPracticas::find_by_ruat_id_and_nro_visita($ruat_id, $nro_visita);
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
                $bpa->nro_visita = $nro_visita;
                $bpa->creador_id = current_user('id');
            }

            $bpa->fecha_visita = $this->input->post('fecha');
            $bpa->conclusion = $this->input->post('conclusionB');
            $bpa->nivel_bpa = $this->input->post('valorFinal');
            $bpa->recomendacion = $this->input->post('recomendacionFinal');
            $bpa->save();


            ///SUBO EL ARCHIVO
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

            // desde aqui
            $idsB = extract_prop($preguntasB, 'id');
            $idsC = extract_prop($preguntasC, 'id');
            // si existe previamente, borre todo
            if($existePreviamente){
                $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpa->id, $idsB), 'order' => 'id'));

                foreach($respuestasB as $respuestaB){
                    $respuestaB->delete();
                }

                $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpa->id, $idsC), 'order' => 'id'));

                foreach($respuestasC as $respuestaC){
                    $respuestaC->delete();
                }
            }
            // ahora solo agregue lo nuevo
            $conteoB = count($preguntasB);
            $conteoC = count($preguntasC);
            $conteo = $conteoB + $conteoC;

            for($i = 1;$i<=$conteo;$i++){ // guardar la parte B
                if($this->input->post('sino'.$i) === FALSE && $this->input->post('recomendacion'.$i) === FALSE){
                    continue;
                }

                $bpaR = new BpaRespuesta();
                $bpaR->bpa_id = $bpa->id;
                $bpaR->pregunta_id = $i;

                if($this->input->post('sino'.$i) == 'on'){
                    $bpaR->puntaje = 1;
                }else{
                    $bpaR->puntaje = 0;
                }

                $bpaR->observacion = $this->input->post('recomendacion'.$i);
                $bpaR->save();
            }

            $estaOFF = false; // el switch de la parte 4.2
            if(!($this->input->post('excepcion42')=='on')){
                $estaOFF = true;
            }
            for($i = 1;$i<=$conteo;$i++){ // guardar la parte c
                if($this->input->post('valor'.$i) === FALSE || ($estaOFF && ($i >= 26 && $i <= 30))){
                    continue;
                }

                $bpaR = new BpaRespuesta();
                $bpaR->bpa_id = $bpa->id;
                $bpaR->pregunta_id = $i;
                $bpaR->puntaje = $this->input->post('valor'.$i);
                $bpaR->observacion = $this->input->post('observacion'.$i);
                $bpaR->save();
            }
            // hasta aqui
            
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
            $respB = array();
            $respC = array();
            $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsB), 'order' => 'id'));
            $inicial = 62;
            foreach($respuestasB as $rB){
                while($rB->pregunta_id != $inicial){
                    $obj = new BpaRespuesta();
                    $obj->pregunta_id = $inicial;
                    $obj->puntaje = false;
                    $obj->observacion = "";
                    array_push($respB, $obj);
                    $inicial++;
                }
                if($rB->puntaje == 1){
                    $rB->puntaje = true;
                }else{
                    $rB->puntaje = false;
                }
                array_push($respB, $rB);
                $inicial++;
            }
            
            
            $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsC), 'order' => 'id'));

            $inicial = 1;
            foreach($respuestasC as $rC){
                while($rC->pregunta_id != $inicial){
                    $obj = new BpaRespuesta();
                    $obj->pregunta_id = $inicial;
                    $obj->puntaje = 0;
                    $obj->observacion = "";
                    array_push($respC, $obj);
                    $inicial++;
                }
                array_push($respC, $rC);
                $inicial++;
            }

            $this->twiggy->set('existe', 'yes');
            //$this->twiggy->set('datosBpa', $datosBPA);

            $condicionExcepcion = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id = ?', $datosBPA->id, 26), 'order' => 'id'));
            if(!count($condicionExcepcion)){
                $this->twiggy->set('excepcion42', false);
            }else{
                $this->twiggy->set('excepcion42', true);
            }
            
            $this->twiggy->set('respuestasB', $respB);
            
            $this->twiggy->set('respuestasC', $respC);
        }else{
            $this->twiggy->set('existe', 'not');
            $this->twiggy->set('excepcion42', true);

        }
 
        $ruat = Ruat::find($ruat_id);
        $this->twiggy->set('ruat', $ruat); //necesario para el encabezado info_productor

        $this->twiggy->set('anterior', $this->puntajes_visita($ruat_id, ((int)$nro_visita)-1));

        $this->twiggy->register_function('form_open_multipart');
        $this->twiggy->set('numForm', $ruatNumFormulario);
        $this->twiggy->set('preguntasB', $preguntasB);
        $this->twiggy->set('preguntasC', $preguntasC);
        $this->twiggy->set('tamaño', count($preguntasC)+count($preguntasB));
        $this->twiggy->set('lista_visitas', $this->gen_lista_visitas($ruat_id, $nro_visita));
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


    private function gen_lista_visitas($ruat_id, $visita_actual)
    {
        $MAX_VISITAS=6;
        $lista = array();
        $agregar = function($nro) use (&$lista, $ruat_id, $visita_actual) {
            $item = array();
            $item['title'] = $nro===0 ? "BPA Inicial" : "Visita ".$nro;
            $item['url'] = site_url(array("bpa","index",$ruat_id,$nro));
            $item['active'] = $nro == $visita_actual;
            $lista[] = $item;
            //echo "Lista es ".json_encode($lista);
        };

        $bpas = BuenasPracticas::find_all_by_ruat_id($ruat_id, array('order' => 'nro_visita'));
        $max = -1;
        foreach($bpas as $bpa) {
            $agregar($bpa->nro_visita);
            $max = $bpa->nro_visita;
        }
        if($max<$MAX_VISITAS) $agregar($max+1);
        return $lista;
    }

    private function puntajes_visita($ruat_id, $nro_visita)
    {
        $dict = array();
        $bpa = BuenasPracticas::find_by_ruat_id_and_nro_visita($ruat_id, $nro_visita);
        if($bpa) {
            $dict['total'] = $bpa->nivel_bpa;
            foreach($bpa->respuestas as $resp) 
                $dict[$resp->pregunta_id] = $resp->puntaje;

        }
        
        return $dict;
    }

}