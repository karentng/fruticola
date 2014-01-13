<?php defined('BASEPATH') OR exit('No direct script access allowed');

class BPA extends CI_Controller {

    public function index()
    {
        //check_profile($this,"Administrador");
        $ruat_id = 1;

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');

        $data = array();
        $preguntasB = BpaPregunta::sortedB();
        $preguntasC = BpaPregunta::sortedC();

        $this->form_validation->set_rules('conclusionB', 'Conclusión', 'required');
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
        }
        else {
            $datosBPA = array('fecha' => date('Y-m-d'), 'conclusion' => '', 'recomendacion' => '', 'nivel_bpa' => 0);
            $this->twiggy->set("datosBPA", $datosBPA);
        }

        

        //$preguntasB = BpaPregunta::find_all_by_seccion('B');
        foreach($preguntasB as $preg) {
            $this->form_validation->set_rules("recomendacion".$preg->id);
        }

        //$preguntasC = BpaPregunta::find_all_by_seccion('C');
        foreach($preguntasC as $preg) {
            $this->form_validation->set_rules("observacion".$preg->id);
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
                $bpaR->observacion = $this->input->post('observacion'.$i);
                $bpaR->save();
            }
            $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'Formulario BPA guardado exitósamente'));
            redirect('listadoruats');
        }else {
            //echo validation_errors();
        }

        
        if($existePreviamente){

            /*$idsB = array();
            foreach($preguntasB as $pregunta){
                array_push($idsB, $pregunta->id);
            }*/
            $idsB = extract_prop($preguntasB, "id");
            
            /*$idsC = array();
            foreach($preguntasC as $pregunta){
                array_push($idsC, $pregunta->id);
            }*/
            $idsC = extract_prop($preguntasC, "id");
            $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsB), 'order' => 'id'));
            $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $datosBPA->id, $idsC), 'order' => 'id'));
            $this->twiggy->set('existe', 'yes');
            //$this->twiggy->set('datosBpa', $datosBPA);
            $this->twiggy->set('respuestasB', $respuestasB);
            $this->twiggy->set('respuestasC', $respuestasC);    
        }else{
            $this->twiggy->set('existe', 'not');

        }


        

        //var_dump($bpaActual[0]->id);

        //var_dump($preguntasC);
        $ruatNumFormulario = Ruat::find($ruat_id)->numero_formulario;
        //$this->twiggy->set('numero_formulario',);
        $this->twiggy->set('numForm', $ruatNumFormulario);
        $this->twiggy->set('preguntasB', $preguntasB);
        $this->twiggy->set('preguntasC', $preguntasC);
        $this->twiggy->set('tamaño', count($preguntasC)+count($preguntasB));

        //$this->twiggy->set($data, NULL);
        //$this->twiggy->set('combos', json_encode($data));
        //$this->twiggy->set('combos', $combos);
        $this->twiggy->template("bpa/bpa");
        $this->twiggy->display();
        /*
        $bpa = Bpa::find_by_ruat_id($ruat_id);
        if($bpa) {
            $bpa = $bpa->to_array();
        }
        else {
            $bpa = array('fecha' => '', 'observacion' => '');
        }

        $this->tiggy->set("bpa", $bpa);

        set_value("fecha", bpa['fecha'])


        array( id => '')

        foreach(Persona::find_all_by(fami)  as $per) {
        $recomendaciones[$per->id] =  $persona;
        }
        $this->twiggy->set(recomendacionse)

        set_value("recomendacion"~preg.id, recomendaciones[$preg.id])
        */
    }

    public function guardar() //si viene como parametro: $ruat_id
    {
        $input = json_decode(file_get_contents("php://input"));
        echo "me llego ";
        var_dump($input);

        //$finca = Finca::find_by_ruat_id($ruat_id);
        //if(!$finca) 
        
    }
}