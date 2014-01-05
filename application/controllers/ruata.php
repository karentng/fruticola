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

        //if(!$depto_id) return;
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
            $ruat->modificador = current_user('id');
            $ruat->save();
            $productor = $ruat->productor;
        }

        $input->contacto->productor_id = $productor->id;
        Contacto::create_or_update((array)$input->contacto);

        $econo = $input->economia;
        if(!$econo->usaCredito) $econo->credito_id = null;
        if($econo->credito_id!=7)  $econo->otro_credito = null;
        unset($econo->usaCredito);
        $econo->productor_id = $productor->id;
        Economia::create_or_update((array)$econo);
        

        foreach($input->innovaciones as $innova) {
            $innovacion = new Innovacion();
            $innovacion->productor_id = $productor->id;
            $innovacion->tipo_id = $innova->tipo;
            if ($innova->fuente!= NULL)
            {
                $innovacion->fuente_id  = $innova->fuente;
                $innovacion->otra_fuente = $innova->cual;
                $innovacion->descripcion = $innova->descripcion;
                $innovacion->save(); 
            }
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



    public function cargar($ruat_id)
    {
        $ruat = Ruat::find($ruat_id);

        $output = new StdClass;
        $output->ruat_id = $ruat->id;
        $output->productor = new StdClass;
        $output->productor->id                = $ruat->productor->id;
        $output->productor->nombre1           = $ruat->productor->nombre1;
        $output->productor->nombre2           = $ruat->productor->nombre2;
        $output->productor->apellido1         = $ruat->productor->apellido1;
        $output->productor->apellido2         = $ruat->productor->apellido2;
        $output->productor->sexo              = $ruat->productor->sexo; 
        $output->productor->nivelEducativo    = $ruat->productor->nivel_educativo_id; 
        $output->productor->tipoDocumento     = $ruat->productor->tipo_documento_id;
        $output->productor->numeroDocumento   = $ruat->productor->numero_documento;
        $output->productor->tipo              = $ruat->productor->tipo_productor_id;
        $output->productor->renglonProductivo = $ruat->productor->renglon_productivo_id;
        $output->productor->fechaNacimiento   = $this->datefmt($ruat->productor->fecha_nacimiento);
        
        $output->contacto = new StdClass;
        $output->contacto->id           = $ruat->productor->contacto->id;
        $output->contacto->celular      = $ruat->productor->contacto->celular;
        $output->contacto->telefono     = $ruat->productor->contacto->telefono;
        $output->contacto->email        = $ruat->productor->contacto->email;
        $output->contacto->departamento = $ruat->productor->contacto->departamento_id;
        $output->contacto->municipio    = $ruat->productor->contacto->municipio_id;
        $output->contacto->vereda       = $ruat->productor->contacto->vereda;
        $output->contacto->direccion    = $ruat->productor->contacto->direccion;
        
        $output->economia = new StdClass;
        $output->economia->ingresoMensual      = $ruat->productor->economia->ingreso_familiar;
        $output->economia->personasCargo       = $ruat->productor->economia->personas_dependientes;
        $output->economia->ingresoAgropecuaria = $ruat->productor->economia->ingreso_agropecuario;
        if($ruat->productor->economia->credito_id!==NULL) {
            $output->economia->usaCredito = true;
            $output->economia->procedenciaCredito = $ruat->productor->economia->credito_id;
            $output->economia->otroCcredito = $ruat->productor->economia->otro_credito;
        }
        else {
            $output->economia->usaCredito = false;
            $output->economia->procedenciaCredito = null;
            $output->economia->otroCcredito = null;
        }

        $output->asociacion = new StdClass;
        function cargarAsociado($asociado) {
            $asoc = new StdClass;
            if($asociado==NULL) {
                $asoc->asociado = false;
                $asoc->nombre    = null;
                $asoc->apellido  = null;
                $asoc->vereda    = null;
                $asoc->confianza = null;
            }
            else {
                $asoc->asociado = true;
                $asoc->nombre    = $asociado->nombre;
                $asoc->apellido  = $asociado->apellido;
                $asoc->vereda    = $asociado->vereda;
                $asoc->confianza = $asociado->grado_confianza_id;
            }
            return $asoc;
        }
        
        $output->asociacion->otroProductor = cargarAsociado($ruat->asociado);
        $output->asociacion->sigue = cargarAsociado($ruat->seguir);

        return $output;
        //echo json_encode($output);
    }

    private function datefmt($f) 
    {
        return $f ? $f->format('Y-m-d') : '';
    }
}