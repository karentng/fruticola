<?php

class RecalcularBPA extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        //echo 3434;
        if(!$this->input->is_cli_request()) show_404();
        
        //check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
    }

    public function recalcular()
    {

        $padre = array();
        $num_hijos = array();

        echo "tin";
        foreach(BpaPregunta::all() as $preg) {
            if($preg->padre) {
                $padre[$preg->id] = $preg->padre;
                if(!isset($num_hijos[$preg->padre])) $num_hijos[$preg->padre] = 0;
                $num_hijos[$preg->padre]++;
            }
            echo "-";
        }


        $bpas = BuenasPracticas::all();//array('include' => array('respuestas')));

        //TODO: eliminar 4.2 e hijos cuando estan en ceros

        echo "inicio..\n";
        foreach($bpas as $bpa) {
            $suma = array();
            foreach($padre as $id => $pdr) {
                if(isset($num_hijos[$id])) $suma[$id] = 0;
            }
            
            foreach($bpa->respuestas as $res) {
                $suma[$padre[$res->pregunta_id]] += $res->puntaje;
            }
            //var_dump($suma);
            //die();

            foreach($bpa->respuestas as $res) {
                if(isset($num_hijos[$res->pregunta_id])) {
                    $antes = $res->puntaje;
                    $res->puntaje = $suma[$res->pregunta_id] / $num_hijos[$res->pregunta_id];
                    if(abs($antes-$res->puntaje)>1) {
                        echo "$antes => $res->puntaje ($bpa->ruat_id) \n";
                        //$res->save;
                    } 
                }
            }
            echo "===================\n";
            //echo $bpa->id."\n";
        }
    }

    function eliminar42()
    {
        $vacias = BpaRespuesta::find_all_by_pregunta_id_and_puntaje(26, 0);
        foreach($vacias as $res) {
            BpaRespuesta::delete_all(array('conditions' => array('bpa_id=? and pregunta_id IN (?)', $res->bpa_id, array(26,27,28,29,30))));

        }
    }   
}