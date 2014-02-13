<?php

class Ubicaciongoogle extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        /*$informes = array(
            array('url' => 'informes/prueba', 'title' => 'Reportico', 'icon' => 'i-file-8', 'color' => 'green'),
            array('url' => 'informes/ruatImprimible', 'title' => 'Imprimir Ruat', 'icon' => 'i-print', 'color' => 'blue'),
            array('url' => 'informes/oto', 'title' => 'Oto Reporte', 'icon' => 'i-file-7', 'color' => 'red')

        );
        
        $this->twiggy->set('informes', $informes);*/
        $result = array();
        $fincas = Finca::find('all', array('order' => 'nombre'));

        foreach($fincas as $finca){
            array_push($result, $finca->to_array());
        }

        $this->twiggy->set('fincas', json_encode($result));

        ///$this->load->library('form_validation');
        $this->crearArchivoJSON($result);
        $this->twiggy->template("reportes/ubicaciongoogle");
        $this->twiggy->display();
        
    }
    public function crearArchivoJSON($fincas){
        $result = array();
        for($i = 0; $i < count($fincas); $i++){
            if($fincas[$i]['geo_latitud'] == 0 && $fincas[$i]['geo_longitud'] == 0){
                continue;
            }
            $aux = array();
            $aux['name'] = $fincas[$i]['nombre'];
            $aux['address'] = Municipio::find_by_id($fincas[$i]['municipio'])->nombre;
            $aux['city'] = "Vereda: ".$fincas[$i]['vereda'];
            $aux['state'] = "Sector: ".$fincas[$i]['sector'];
            $aux['postal'] = "";//"Área total: ".$fincas[$i]['area_total'];
            $aux['phone'] = "";
            $aux['web'] = "";
            $aux['lat'] = $fincas[$i]['geo_latitud'];
            $aux['lng'] = $fincas[$i]['geo_longitud'];
            array_push($result, $aux);
        }
        $fp = fopen('assets/google_maps/results.json', 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);
    }
}