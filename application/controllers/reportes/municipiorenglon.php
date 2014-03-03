<?php

class MunicipioRenglon extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        //$this->twiggy->set('municipios', $municipios);
        $tabla = array();
        $totalMunicipios = array();
        $totalRenglon = array();
        $total = 0;

        $renglones = RenglonProductivo::sorted();
        $municipios = Municipio::find('all', array('conditions' => array('departamento_id = ?', 30), 'order' => 'nombre'));

        foreach($renglones as $renglon){
            array_push($totalRenglon, 0);
        }

        foreach($municipios as $municipio){
            array_push($totalMunicipios, 0);
            $mun = array();
            for($i = 0; $i < count($renglones) ; $i++){
                array_push($mun, 0);
            }
            
            $fincas = Finca::find('all', array('conditions' => array('municipio_id = ?', $municipio->id)));
            $ruats = extract_prop($fincas, 'ruat_id');
            
            if(count($ruats) == 0){
                array_push($tabla, $mun);
                continue;
            }
            
            foreach($ruats as $r){
                $productor = Ruat::find('all', array('conditions' => array('id = ?', $r)));
                $productor = $productor[0];
                $productor = $productor->productor_id;
                $ren = Productor::find('all', array('conditions' => array('id = ?', $productor), 'order' => 'renglon_productivo_id'));
                $ren = $ren[0];
                $ren = $ren->renglon_productivo_id;
                $mun[$ren-1] += 1;
            }
            array_push($tabla, $mun);
        }

        for($i = 0 ; $i < count($tabla) ; $i++){
            for($j = 0 ; $j < count($tabla[$i]) ; $j++){
                $totalMunicipios[$i] += $tabla[$i][$j];
                $total += $tabla[$i][$j];
                $totalRenglon[$j] += $tabla[$i][$j];
            }
        }
        /*var_dump($totalMunicipios);
        var_dump($totalRenglon);*/

        $this->twiggy->set('renglones', $renglones);
        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('info', $tabla);
        $this->twiggy->set('total', $total);
        $this->twiggy->set('totalM', $totalMunicipios);
        $this->twiggy->set('totalR', $totalRenglon);

        $this->twiggy->set('infoJSON', json_encode($tabla));
        $aux = array();
        $aux2 = array();
        foreach($renglones as $reng){
            array_push($aux, $reng->to_array());
        }
        foreach($municipios as $mun){
            array_push($aux2, $mun->to_array());
        }
        $this->twiggy->set('renglonesJSON', json_encode($aux));
        $this->twiggy->set('municipiosJSON', json_encode($aux2));

        $this->twiggy->template("reportes/municipiorenglon");
        $this->twiggy->display();
    }
}