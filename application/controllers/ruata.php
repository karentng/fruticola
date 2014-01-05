<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatA extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function index($ruat_id=NULL)
    {
        check_profile($this, 'Administrador');
                        
        

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

        //$this->twiggy->set($data, NULL);
        $this->twiggy->set('combos', json_encode($data));

        if($ruat_id) {
            $ruat = $this->cargar($ruat_id);
            $this->twiggy->set('ruat', json_encode($ruat));
        }

        $this->twiggy->template("ruat/ruata");
        $this->twiggy->display();
    }

    public function municipios($depto_id)
    {
        $municipios = Municipio::find_all_by_departamento_id($depto_id);//, array('order' => 'nombre'));
        echo "<option></option>";
        foreach($municipios as $mun) {
            echo "<option value='$mun->id'>$mun->nombre</option>\n";
        }
    }

    public function guardar()
    {
        
        $input = json_decode(file_get_contents("php://input"));
        
        if(empty($input->ruat_id)) {
            $ruat = new Ruat;
            $ruat->creador_id = current_user('id');
            $productor = Productor::create((array)$input->productor);
            $ruat->productor_id = $productor->id;
            $ruat->save();
        }
        else {
            $ruat = Ruat::find($input->ruat_id);
            $ruat->modificado = time();
            $ruat->modificador_id = current_user('id');
            $ruat->save();
            $productor = $ruat->productor;
            $productor->set_attributes((array)$input->productor);
            $productor->save();
        }

        $input->contacto->productor_id = $productor->id;
        Contacto::create_or_update((array)$input->contacto);

        $econo = $input->economia;
        if(!$econo->usaCredito) $econo->credito_id = null;
        if($econo->credito_id!=7)  $econo->otro_credito = null;
        unset($econo->usaCredito);
        $econo->productor_id = $productor->id;
        Economia::create_or_update((array)$econo);
        
        Innovacion::table()->delete(array('ruat_id' => $ruat->id));
        foreach($input->innovaciones as $innova) {
            if(!$innova->fuente_id) continue;
            $innova->ruat_id = $ruat->id;
            if($innova->fuente_id!=6) $innova->otra_fuente = null;
            Innovacion::create((array)$innova);
        }

        Orgasociada::table()->delete(array('ruat_id' => $ruat->id));
        RazonNoPertenecer::table()->delete(array('ruat_id' => $ruat->id));
        
        foreach($input->asociacion->cooperativa->filas as $org) {
            $clases = $org->clases;
            $beneficios = $org->beneficios;
            $directivo = $org->directivo;
            unset($org->clases, $org->beneficios, $org->directivo);
            $org->ruat_id = $ruat->id;
            $org->membresia = $org->directivo ? 'Directivo' : 'Participante';

            $orgasociada = Orgasociada::create((array)$org);

            foreach($clases as $cls)
                OrgasociadaClase::create(array(
                    'orgasociada_id' => $orgasociada->id, 'clase_id' => $cls) );

            foreach($beneficios as $bnf)
                OrgasociadaBeneficio::create(array(
                    'orgasociada_id' => $orgasociada->id, 'beneficio_id' => $bnf ));
        }
        

        foreach ($input->asociacion->cooperativa->razones as $razon)
            RazonNoPertenecer::create(array('ruat_id' => $ruat->id, 'razon_id' => $razon));
        
        $ruat->orgs_apoyan = json_encode($input->asociacion->cooperativa->orgs_apoyan);

        if($input->asociacion->otroProductor->asociado) {
            $asoc = $input->asociacion->otroProductor;
            unset($asoc->asociado);
            $per = PersonaAsociada::create_or_update((array)$asoc);
            $ruat->asociado_id = $per->id;
        }
        else if($ruat->asociado_id) {
            PersonaAsociada::table()->delete(array('id' => $ruat->asociado_id));
            $ruat->asociado_id = null;
        }

        if($input->asociacion->sigue->asociado) {
            $asoc = $input->asociacion->sigue;
            unset($asoc->asociado);
            $per = PersonaAsociada::create_or_update((array)$asoc);
            $ruat->seguir_id = $per->id;
        }
        else if($ruat->seguir_id) {
            PersonaAsociada::table()->delete(array('id' => $ruat->seguir_id));
            $ruat->seguir_id = null;
        }  

        
        $ruat->save();
        echo "ok";
    }



    public function cargar($ruat_id, $do_echo=false)
    {
        $ruat = Ruat::find($ruat_id);

        $output = new StdClass;
        $output->ruat_id = $ruat->id;
        $output->productor = $ruat->productor->to_array();
        $output->productor['fecha_nacimiento'] = $ruat->productor->fecha_nacimiento->format("Y-m-d");
        $output->contacto = $ruat->productor->contacto->to_array();
        $output->economia = $ruat->productor->economia->to_array();
        $output->economia['usaCredito'] = (bool)($output->economia['credito_id']);
        $output->asociacion = array(
            'cooperativa'   => array('filas' => array()),
            'otroProductor' => array('asociado' => false),
            'sigue'         => array('asociado' => false),
        );
            
        $coops = Orgasociada::find_all_by_ruat_id($ruat->id, array('include' =>array('clases','beneficios')));
        foreach($coops as $org) {
            $orgasociada = $org->to_array();
            $orgasociada['directivo']  = $orgasociada['membresia']=='Directivo';
            $orgasociada['clases']     = extract_prop($org->clases, 'clase_id');
            $orgasociada['beneficios'] = extract_prop($org->beneficios, 'beneficio_id');
            $output->asociacion['cooperativa']['filas'][] = $orgasociada;
        }
        $output->asociacion['cooperativa']['asociado'] = (bool)count($output->asociacion['cooperativa']['filas']);
        $output->asociacion['cooperativa']['orgs_apoyan'] = json_decode($ruat->orgs_apoyan);
        $output->asociacion['cooperativa']['razones'] = extract_prop(RazonNoPertenecer::find_all_by_ruat_id($ruat->id),'razon_id');

        if($ruat->asociado_id) {
            $output->asociacion['otroProductor'] = PersonaAsociada::find($ruat->asociado_id)->to_array();
            $output->asociacion['otroProductor']['asociado'] = true;
        }
        if($ruat->seguir_id) {
            $output->asociacion['sigue'] = PersonaAsociada::find($ruat->seguir_id)->to_array();
            $output->asociacion['sigue']['asociado'] = true;
        }
        
        $output->innovaciones = array();
        foreach(Innovacion::find_all_by_ruat_id($ruat->id) as $inno) {
            $output->innovaciones[] = $inno->to_array();
        }
        $output->realizaInnovacion = (bool)count($output->innovaciones);
        if($do_echo) echo json_encode($output);
        return $output;
        //echo json_encode($output);
    }

    private function datefmt($f) 
    {
        return $f ? $f->format('Y-m-d') : '';
    }

    
}