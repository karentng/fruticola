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

        if ($this->form_validation->run()) {
            $bpa = new BuenasPracticas();
            $bpa->ruat_id = $ruat_id;
            $bpa->fecha = $this->input->post('fecha');
            $bpa->conclusion = $this->input->post('conclusionB');
            $bpa->nivel_bpa = $this->input->post('valorFinal');
            $bpa->recomendacion = $this->input->post('recomendacionFinal');
            //var_dump($bpa);
            $bpa->save();

            $conteoB = count($preguntasB);
            $conteoC = count($preguntasC);
            $conteo = $conteoB + $conteoC;

            for($i = 1;$i<=$conteo;$i++){
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

            for($i = 1;$i<=$conteo;$i++){
                if($this->input->post('valor'.$i) === FALSE){
                    continue;
                }
                $bpaR = new BpaRespuesta();
                $bpaR->bpa_id = $bpa->id;
                $bpaR->pregunta_id = $i;
                $bpaR->puntaje = $this->input->post('valor'.$i);
                $bpaR->observacion = $this->input->post('observacion'.$i);
                $bpaR->save();
            }
        }else {
            echo validation_errors();
        }

        //var_dump($preguntasC);
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