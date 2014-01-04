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

        $this->twiggy->set($data, NULL);
        $this->twiggy->set('combos', json_encode($data));
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
            $productor = new Productor();
        }
        else {
            $ruat = Ruat::find($input->ruat_id);
            $ruat->modificado = time();
            $ruat->modificador = current_user('id');
            $ruat->save();
            $productor = $ruat->productor;
        }

        $productor->nombre1     = $input->productor->nombre1;
        $productor->nombre2     = $input->productor->nombre2;
        $productor->apellido1   = $input->productor->apellido1;
        $productor->apellido2   = $input->productor->apellido2;
        $productor->sexo        = $input->productor->sexo;
        $productor->fecha_nacimiento      = $input->productor->fechaNacimiento;
        $productor->nivel_educativo_id    = $input->productor->nivelEducativo;
        $productor->tipo_documento_id     = $input->productor->tipoDocumento;
        $productor->numero_documento      = $input->productor->numeroDocumento;
        $productor->renglon_productivo_id = $input->productor->renglonProductivo;
        $productor->tipo_productor_id     = $input->productor->tipo;
        
        $productor->save();

        if(!$ruat->id) {
            $ruat->productor_id = $productor->id;
            $ruat->save();
        }

        $contacto = new Contacto();
        $contacto->productor_id     = $productor->id;
        $contacto->telefono         = $input->contacto->telefono;
        $contacto->celular          = $input->contacto->celular;
        $contacto->email            = $input->contacto->email;
        $contacto->departamento_id  = $input->contacto->departamento;
        $contacto->municipio_id     = $input->contacto->municipio;
        $contacto->vereda           = $input->contacto->vereda;
        $contacto->direccion        = $input->contacto->direccion;
        $contacto->save();


        $economia = new Economia();
        $economia->productor_id     = $productor->id;
        $economia->ingreso_familiar = $input->economia->ingresoMensual;
        $economia->ingreso_agropecuario = $input->economia->ingresoAgropecuaria;
        $economia->personas_dependientes = $input->economia->personasCargo;
        $economia->credito_id = $input->economia->procedenciaCredito;
        $economia->otro_credito = $input->economia->otroCredito;

        $economia->save();

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

        foreach($input->asociacion->cooperativa->filas as $fila) {
            $orgasociada = new Orgasociada();
            $orgasociada->productor_id = $productor->id;
            $orgasociada->nombre = $fila->organizacion;
            $orgasociada->periodicidad_id = $fila->periodicidad;
            $orgasociada->directivo = $fila->directivo;
            $orgasociada->participante = $fila->participante;
            $orgasociada->save();

            foreach ($fila->beneficios as $beneficio) {
                $orgasociada_beneficio = new OrgasociadaBeneficio();
                $orgasociada_beneficio->orgasociada_id = $orgasociada->id;
                $orgasociada_beneficio->beneficio_id = $beneficio;
                $orgasociada_beneficio->save();
            }

            foreach ($fila->clases as $clase) {
                $orgasociada_clase = new OrgasociadaClase();
                $orgasociada_clase->orgasociada_id = $orgasociada->id;
                $orgasociada_clase->clase_id = $clase;
                $orgasociada_clase->save();
            }
            
        }

        foreach ($input->asociacion->cooperativa->razones as $razon) {
            $razonnopertenecer = new RazonesNoPertenecer();
            $razonnopertenecer->productor_id = $productor->id;
            $razonnopertenecer->razon_id = $razon;
            $razonnopertenecer->save();
        }

        /*oreach ($input->asociacion->cooperativa->apoyan as $apoyo) {
            $entidadApoyo = new EntidadesApoyo(); // falta crear modelo
            $entidadApoyo ->productor_id = $productor->id;
            $entidadApoyo ->entidadApoyo= $apoyo;
            $entidadApoyo ->save();
        }*/

        $personaasociada = new PersonaAsociada();
        //$personaasociada->productor_id = $productor->id;
        $personaasociada->nombre       = $input->asociacion->otroProductor->nombre;
        $personaasociada->apellido     = $input->asociacion->otroProductor->apellido;
        $personaasociada->vereda       = $input->asociacion->otroProductor->vereda;
        $personaasociada->confianza_id = $input->asociacion->otroProductor->confianza;
        $personaasociada->save();


        /*$personaseguir = new PersonaSeguir();
        $personaseguir->productor_id = $productor->id;
        $personaseguir->nombre = $input->asociacion->sigue->nombre;
        $personaseguir->apellido = $input->asociacion->sigue->apellido;
        $personaseguir->vereda = $input->asociacion->sigue->vereda;
        $personaseguir->grado_confianza = $input->asociacion->sigue->confianza;
        $personaseguir->save();*/


        echo "ok";
    }



    public function cargar($ruat_id)
    {
        $ruat = Ruat::find($ruat_id);

        $output = new StdClass;
        $output->ruat_id = $ruat->id;
        $output->productor = new StdClass;
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

        echo json_encode($output);
    }

    private function datefmt($f) 
    {
        return $f ? $f->format('Y-m-d') : '';
    }
}