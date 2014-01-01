<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bpa extends CI_Controller {

    public function index()
    {
        //check_profile($this,"Administrador");
         function to_array($model) { return $model->to_array(); }

        $data = array();
        $data['tiposDocumento']       = array_map('to_array',TipoDocumento::sorted());
        $data['nivelesEducativos']    = array_map('to_array',NivelEducativo::sorted());
        $data['tiposProductor']       = array_map('to_array',TipoProductor::sorted());
        $data['renglonesProductivos'] = array_map('to_array',RenglonProductivo::sorted());
        $data['clasesOrganizaciones'] = array_map('to_array',ClaseOrganizacion::sorted());
        $data['tiposBeneficio']       = array_map('to_array',TipoBeneficio::sorted());
        $data['tiposCredito']         = array_map('to_array',TipoCredito::sorted());
        $data['periodicidades']       = array_map('to_array',Periodicidad::sorted());
        $data['tiposConfianza']       = array_map('to_array',TipoConfianza::sorted());
        $data['tiposInnovacion']      = array_map('to_array',TipoInnovacion::sorted());
        $data['fuentesInnovacion']    = array_map('to_array',FuenteInnovacion::sorted());
        $data['tiposRazonNoPertenecer'] = array_map('to_array',TipoRazonNoPertenecer::sorted());
        //$data['departamentos'] = array_map('to_array',Departamento::all(array('order'=>'nombre')), 'id', 'nombre');
        $deptos = Departamento::all(array('order' => 'nombre', 'include' => array('municipios')));
        $deptos_municipios = array();
        foreach($deptos as $depto) {
            $deptos_municipios[$depto->id] = array('nombre'=>$depto->nombre, 'municipios'=>array());
            foreach($depto->municipios as $mun)
                $deptos_municipios[$depto->id]['municipios'][] = array('id' => $mun->id, 'nombre'=>$mun->nombre);
        }

        $data['departamentos'] = $deptos_municipios;

        //var_dump($tiposDocumento);
        //die();

        $this->twiggy->set($data, NULL);
        $this->twiggy->set('combos', json_encode($data));
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