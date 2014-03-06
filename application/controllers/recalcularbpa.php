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
        $this->eliminar42();
        $padre = array();
        $num_hijos_orig = array();

        echo "tin";
        foreach(BpaPregunta::all() as $preg) {
            if($preg->padre) {
                $padre[$preg->id] = $preg->padre;
                if(!isset($num_hijos_orig[$preg->padre])) $num_hijos_orig[$preg->padre] = 0;
                $num_hijos_orig[$preg->padre]++;
            }
            echo "-";
        }


        $bpas = BuenasPracticas::all();//array('include' => array('respuestas')));

        
        echo "inicio..\n";
        foreach($bpas as $index => $bpa) {
            $suma = array();
            foreach($padre as $id => $pdr) {
                if(isset($num_hijos[$id])) $suma[$id] = 0;
            }
            $num_hijos = $num_hijos_orig;
            $num_hijos[14]--;
            foreach($bpa->respuestas as $res) {
                //if($res->pregunta_id==14 && $res->puntaje==0) { //pregunta 4, cuando switch es off
                if($res->pregunta_id==26) $num_hijos[14]++;
                //} 
                $suma[$padre[$res->pregunta_id]] += $res->puntaje;
            }
            //var_dump($suma);
            //die();
            $nuevoTotal = 0;
            foreach($bpa->respuestas as $res) {
                if(isset($num_hijos[$res->pregunta_id])) {
                    $antes = $res->puntaje;
                    $res->puntaje = round($suma[$res->pregunta_id] / $num_hijos[$res->pregunta_id],2);
                    if(abs($antes-$res->puntaje)>1) {
                        echo "<$res->pregunta_id> $antes => $res->puntaje ($bpa->ruat_id) \n";
                        $res->save();
                    } 
                }

                if(in_array($res->pregunta_id, array(1,8,12,14,37,41,48,54)) )
                    $nuevoTotal += $res->puntaje;

            }

            $nuevoNivel = round($nuevoTotal/8.0, 2);
            if(abs($nuevoNivel-$bpa->nivel_bpa)>1) {
                echo "**** <$bpa->ruat_id>  $bpa->nivel_bpa  => $nuevoNivel \n";
                $bpa->nivel_bpa = $nuevoNivel;
                $bpa->save();
            }

             //liberar memoria
            foreach($bpa->respuestas as $idx => $res) {
                unset($bpa->respuestas[$idx]);
            }
            unset($bpa->respuestas);
            unset($bpas[$index]);
            if($index%10==0) gc_collect_cycles();
        }
    }

    function eliminar42()
    {
        $vacias = BpaRespuesta::find_all_by_pregunta_id_and_puntaje(26, 0);
        foreach($vacias as $res) {
            BpaRespuesta::delete_all(array('conditions' => array('bpa_id=? and pregunta_id IN (?)', $res->bpa_id, array(26,27,28,29,30))));
            echo "eliminando $res->bpa_id \n";
        }
    }   
}