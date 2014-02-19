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
        $result2 = array();
        $productores = array();
        $renglon_productivo = array();

        $fincas = Finca::find('all', array('order' => 'nombre', 'conditions' => array('(geo_latitud > ? OR geo_latitud < ?) 
            AND (geo_longitud > ? OR geo_longitud < ?)', 0, 0, 0, 0)));
        $municipios = Municipio::find('all');

        foreach($fincas as $finca){
            
            $ruat = Ruat::find_by_id($finca->ruat_id);
            $prod = Productor::find_by_id($ruat->productor_id);

            array_push($productores, $prod->nombre1 ." ". $prod->nombre2 ." ". $prod->apellido1 ." ". $prod->apellido2);

            $renglon = RenglonProductivo::find_by_id($prod->renglon_productivo_id);

            array_push($renglon_productivo, $renglon->descripcion);

            array_push($result, $finca->to_array());
        }

        foreach($municipios as $m){
            array_push($result2, $m->to_array());
        }

        $this->twiggy->set('fincas', json_encode($result));
        $this->twiggy->set('municipiosJSON', json_encode($result2));
        
        $this->twiggy->set('productoresJSON', json_encode($productores));
        $this->twiggy->set('renglonJSON', json_encode($renglon_productivo));

        $this->twiggy->set('municipios', $municipios);

        $this->twiggy->template("reportes/ubicaciongoogle");
        $this->twiggy->display();
    }

    public function fincasPorMunicipio(){
        $id = $_POST['id'];

        $productores = array();
        $renglon_productivo = array();

        $fincas = Finca::find('all', array('conditions' => array('municipio_id = ?', $id)));
        $result = array();
        foreach($fincas as $finca){
            $ruat = Ruat::find_by_id($finca->ruat_id);
            $prod = Productor::find_by_id($ruat->productor_id);

            array_push($productores, $prod->nombre1 ." ". $prod->nombre2 ." ". $prod->apellido1 ." ". $prod->apellido2);

            $renglon = RenglonProductivo::find_by_id($prod->renglon_productivo_id);

            array_push($renglon_productivo, $renglon->descripcion);
            array_push($result, $finca->to_array());
        }
        $final = array();
        array_push($final, $result);
        array_push($final, $productores);
        array_push($final, $renglon_productivo);
        echo json_encode($final);
    }
}