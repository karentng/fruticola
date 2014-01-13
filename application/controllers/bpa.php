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

        //$respuestas_bpa = BpaRespuesta::all(array(
        //            'conditions' => array('bpa_id = ?', $ruat_id)));

        if ($this->form_validation->run()) {

            //$bpa = $bpa_id ? BuenasPracticas::find($bpa_id) : new BuenasPracticas();

            $bpa = BuenasPracticas::all(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)));
            //var_dump($bpa);
            if(count($bpa) == 1){// existe
                $bpa = $bpa[0];
                //$bpa = $bpa[0];
                //var_dump("aaa");
            }else{
                $bpa = new BuenasPracticas();
                $bpa->ruat_id = $ruat_id;
            }

            //$bpa = new BuenasPracticas();
            $bpa->fecha = $this->input->post('fecha');
            $bpa->conclusion = $this->input->post('conclusionB');
            $bpa->nivel_bpa = $this->input->post('valorFinal');
            $bpa->recomendacion = $this->input->post('recomendacionFinal');
            //var_dump($bpa);
            $bpa->save();

            $conteoB = count($preguntasB);
            $conteoC = count($preguntasC);
            $conteo = $conteoB + $conteoC;

            $cont = 0;
            for($i = 1;$i<=$conteo;$i++){
                if($this->input->post('sino'.$i) === FALSE && $this->input->post('recomendacion'.$i) === FALSE){
                    continue;
                }

                $preguntasB = BpaPregunta::all(array(
                    'conditions' => array('seccion = ? ', 'B')));

                $idsB = array();
                foreach($preguntasB as $pregunta){
                    array_push($idsB, $pregunta->id);
                }

                $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpa->id, $idsB), 'order' => 'id'));

                $bpaR; //= BpaRespuesta::all(array(
                    //'conditions' => array('bpa_id = ?', $bpa->id), 'order' => 'id'));
                    //var_dump($respuestasB);
                if(count($respuestasB)>0){
                    //var_dump($i-1);
                    $bpaR = $respuestasB[$cont];
                    $cont++;
                    //var_dump($bpaR);
                }else{
                    $bpaR = new BpaRespuesta();
                    $bpaR->bpa_id = $bpa->id;
                    var_dump($bpaR);
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
            for($i = 1;$i<=$conteo;$i++){
                if($this->input->post('valor'.$i) === FALSE){
                    continue;
                }

                $preguntasC = BpaPregunta::all(array(
                    'conditions' => array('seccion = ? ', 'C')));

                $idsC = array();
                foreach($preguntasC as $pregunta){
                    array_push($idsC, $pregunta->id);
                }

                $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpa->id, $idsC), 'order' => 'id'));

                $bpaR;
                if(count($respuestasC)>0){
                    $bpaR = $respuestasC[$cont];
                    $cont++;
                }else{
                    $bpaR = new BpaRespuesta();
                    $bpaR->bpa_id = $bpa->id;

                }

                //$bpaR = new BpaRespuesta();
                //$bpaR->bpa_id = $bpa->id;
                $bpaR->pregunta_id = $i;
                $bpaR->puntaje = $this->input->post('valor'.$i);
                $bpaR->observacion = $this->input->post('observacion'.$i);
                $bpaR->save();
            }
        }else {
            echo validation_errors();
        }


        $bpaActual = BuenasPracticas::all(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)));
        //var_dump($bpaActual);
        
        if(count($bpaActual) == 1){

            $preguntasB = BpaPregunta::all(array(
                    'conditions' => array('seccion = ? ', 'B')));

            $preguntasC = BpaPregunta::all(array(
                    'conditions' => array('seccion = ? ', 'C')));

            $idsB = array();
            foreach($preguntasB as $pregunta){
                array_push($idsB, $pregunta->id);
            }
            
            $idsC = array();
            foreach($preguntasC as $pregunta){
                array_push($idsC, $pregunta->id);
            }

            $respuestasB = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpaActual[0]->id, $idsB), 'order' => 'id'));
            $respuestasC = BpaRespuesta::all(array(
                    'conditions' => array('bpa_id = ? AND pregunta_id in (?)', $bpaActual[0]->id, $idsC), 'order' => 'id'));
            
            $this->twiggy->set('existe', 'yes');
            $this->twiggy->set('datosBpa', $bpaActual);
            $this->twiggy->set('respuestasB', $respuestasB);
            $this->twiggy->set('respuestasC', $respuestasC);    
        }else{
            $this->twiggy->set('existe', 'not');
        }

        //var_dump($bpaActual[0]->id);

        //var_dump($preguntasC);
        $this->twiggy->set('ruat_id', $ruat_id);
        $this->twiggy->set('preguntasB', $preguntasB);
        $this->twiggy->set('preguntasC', $preguntasC);
        $this->twiggy->set('tamaño', count($preguntasC)+count($preguntasB));

        //$this->twiggy->set($data, NULL);
        //$this->twiggy->set('combos', json_encode($data));
        //$this->twiggy->set('combos', $combos);
        $this->twiggy->template("ruat/bpa");
        $this->twiggy->display();
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