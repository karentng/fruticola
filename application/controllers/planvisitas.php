<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PlanVisitas extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        
        check_profile($this, "Administrador", "Coordinador", "Digitador");
    }

    public function index($ruat_id=NULL)
    {
                        
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
        $deptos = Departamento::all(array('order' => 'nombre', 'include' => array('municipios')));
        $deptos_municipios = array();
        foreach($deptos as $depto) {
            $deptos_municipios[$depto->id] = array('nombre'=>$depto->nombre, 'municipios'=>array());
            foreach($depto->municipios as $mun)
                $deptos_municipios[$depto->id]['municipios'][] = array('id' => $mun->id, 'nombre'=>$mun->nombre);
        }

        $data['departamentos'] = $deptos_municipios;

        $this->twiggy->set('combos', json_encode($data));

        if($ruat_id) {
            $ruat = $this->cargar($ruat_id);
            $this->twiggy->set('ruat', json_encode($ruat));
        }

        $this->twiggy->template("ruat/planvisitas");
        $this->twiggy->display();
    }


    public function guardar()
    {
        $input = json_decode(file_get_contents("php://input"));
        
        if(empty($input->ruat_id)) {
            $ruat = new Ruat;
            $ruat->numero_formulario= $input->numero_formulario;
            $ruat->creador_id = current_user('id');
            $productor = Productor::create((array)$input->productor);
            $ruat->productor_id = $productor->id;
            $ruat->save();
        }
        else {
            $ruat = Ruat::find($input->ruat_id);
            $ruat->numero_formulario = $input->numero_formulario;
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
                    'orgasociada_id' => $orgasociada->id, 'beneficio_id' => $bnf) );
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
            $id_to_delete = $ruat->asociado_id;
            $ruat->asociado_id = null;
            $ruat->save();
            PersonaAsociada::table()->delete(array('id' => $id_to_delete));
        }

        if($input->asociacion->sigue->asociado) {
            $asoc = $input->asociacion->sigue;
            unset($asoc->asociado);
            $per = PersonaAsociada::create_or_update((array)$asoc);
            $ruat->seguir_id = $per->id;
        }
        else if($ruat->seguir_id) {
            $id_to_delete = $ruat->seguir_id;
            $ruat->seguir_id = null;
            $ruat->save();
            PersonaAsociada::table()->delete(array('id' => $id_to_delete));
        }  

        $ruat->save();
        $response = array(
            'success'=>true, 
            'message'=> array('type'=>'success', 'text'=>'Guardado Exitoso'),
            'scope'=>$this->cargar($ruat->id)
        );
        
        echo json_encode($response);
    }


    public function cargar($ruat_id, $do_echo=false)
    {
        $ruat = Ruat::find($ruat_id);
        $output = new StdClass;
        $output->soloLectura = true;
        $output->ruat_id = $ruat->id;
        $output->numero_formulario = $ruat->numero_formulario;
        $output->productor = $ruat->productor->to_array();
        $output->productor['fecha_nacimiento'] = $this->datefmt($ruat->productor->fecha_nacimiento);
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


        if(!count($output->asociacion['cooperativa']['filas'])) {
            $output->asociacion['cooperativa']['filas'][] = new StdClass; //filita vacia
            $output->asociacion['cooperativa']['asociado'] = false;
        }
        else
            $output->asociacion['cooperativa']['asociado'] = true;

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
        
        $inno_map = array();
        foreach(Innovacion::find_all_by_ruat_id($ruat->id) as $inno) {
            $inno_map[$inno->tipo_id] = $inno->to_array();
        }

        $output->innovaciones = array();
        foreach(TipoInnovacion::sorted() as $t) {
            if(isset($inno_map[$t->id]))
                $output->innovaciones[] = $inno_map[$t->id];
            else 
                $output->innovaciones[] = array('tipo_id'=>$t->id);
        }

        $output->realizaInnovacion = (bool)count($output->innovaciones);
        
        if($do_echo) echo json_encode($output);
        return $output;
    }


    private function datefmt($f) 
    {
        return $f ? $f->format('Y-m-d') : '';
    }
}
