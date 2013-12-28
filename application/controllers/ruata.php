<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatA extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function index()
    {
        check_profile($this, 'Administrador');
        //$prueba = $this->input->post('prueba[]');
        //var_dump($prueba);

                        
        $this->load->library('form_validation');
        //poner validaciones aqui. Ejemplo:
        //$this->form_validation->set_rules('name','Nombre', 'required|max_length[50]');
        //-------------------------------------------------------------------------------
        // PARTE 1
        $this->form_validation->set_rules('nombre1','Primer Nombre', 'trim|required');
        $this->form_validation->set_rules('nombre2');
        $this->form_validation->set_rules('apellido1','Primer Apellido', 'trim|required');
        $this->form_validation->set_rules('apellido2');        
        $this->form_validation->set_rules('tipo_documento', 'Tipo de Documento', 'required');
        $this->form_validation->set_rules('numero_documento', 'Numero de Documento','required|is_natural');
        $this->form_validation->set_rules('fecha_nacimiento', 'Fecha de Nacimiento', 'required|callback__date_check');
        $this->form_validation->set_rules('sexo', 'Sexo', 'required');
        $this->form_validation->set_rules('nivel_educativo', 'Nivel Educativo', 'required');
        $this->form_validation->set_rules('tipo_productor', 'Tipo de Productor', 'required');
        $this->form_validation->set_rules('renglon_productivo', 'Renglon Productivo', 'required');

        /*
        //-------------------------------------------------------------------------------------------
        //PARTE 2
        $this->form_validation->set_rules('telefono_fijo');
        $this->form_validation->set_rules('celular');
        $this->form_validation->set_rules('correo');
        $this->form_validation->set_rules('vereda');
        $this->form_validation->set_rules('direccion');

        //------------------------------------------------------------------------------------
        //PARTE 3
        $this->form_validation->set_rules('ingreso_familiar', 'Ingreso Mensual Familiar', 'decimal');
        $this->form_validation->set_rules('personas_cargos', 'Personas a Cargo', 'numeric');
        $this->form_validation->set_rules('ingreso_agropecuaria', 'Ingreso de Actividad Agropecuaria', 'decimal');
        $this->form_validation->set_rules('tipoCredito', 'Procedencia del Crédito', 'required');
        $this->form_validation->set_rules('cual_procedencia', 'Procedencia del Crédito', 'required');
        //------------------------------------------------------------------------------------------------
        //PARTE 4
        $this->form_validation->set_rules('beneficiosSociedad', 'Beneficios de Sociedades','required');
        
        $this->form_validation->set_rules('periodicidad1', 'Periodicidad', 'required');
        $this->form_validation->set_rules('periodicidad2', 'Periodicidad', 'required');
        $this->form_validation->set_rules('periodicidad3', 'Periodicidad', 'required');
        
        
        $this->form_validation->set_rules('clases_organizacion1', 'Clase de Organización', 'required');
        $this->form_validation->set_rules('clases_organizacion2', 'Clase de Organización', 'required');
        $this->form_validation->set_rules('clases_organizacion3', 'Clase de Organización', 'required');

        //----------------------------------------------------------------------------------------------
        //PARTE 5
        $this->form_validation->set_rules('nombre_socio');
        $this->form_validation->set_rules('apellido_socio');
        $this->form_validation->set_rules('vereda_socio');
        $this->form_validation->set_rules('gradoConfianza1', 'Grado de Confianza', 'required');

        $this->form_validation->set_rules('nombre_seguir');
        $this->form_validation->set_rules('apellido_seguir');
        $this->form_validation->set_rules('vereda_seguir');
        $this->form_validation->set_rules('gradoConfianza2', 'Grado de Confianza', 'required');
        */

        if($this->form_validation->run())
        {
            //las validaciones pasaron, aqui iria la logica de insertar en la BD...
            $productor = new Productor;
            $productor->nombre1               = $this->input->post('nombre1');
            $productor->nombre2               = $this->input->post('nombre2');
            $productor->apellido1             = $this->input->post('apellido1');
            $productor->apellido2             = $this->input->post('apellido2');
            $productor->tipo_documento_id     = $this->input->post('tipo_documento');
            $productor->numero_documento      = $this->input->post('numero_documento');
            $productor->fecha_nacimiento      = $this->input->post('fecha_nacimiento');
            $productor->nivel_educativo_id    = $this->input->post('nivel_educativo');
            $productor->tipo_productor_id     = $this->input->post('tipo_productor');
            $productor->renglon_productivo_id = $this->input->post('renglon_productivo');
            $productor->sexo                  = $this->input->post('sexo');
            $productor->save();

        }

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
        
        
        
        
        $productor = new Productor();
        
        
        $productor->nombre1     = $input->productor->nombre1;
        $productor->nombre2     = $input->productor->nombre2;
        $productor->apellido1   = $input->productor->apellido1;
        $productor->apellido2   = $input->productor->apellido2;
        $productor->sexo        = $input->productor->sexo;
        $productor->fecha_nacimiento = $input->productor->fechaNacimiento;
        $productor->nivel_educativo_id = $input->productor->nivelEducativo;
        $productor->tipo_documento_id = $input->productor->tipoDocumento;
        $productor->numero_documento = $input->productor->numeroDocumento;
        $productor->renglon_productivo_id= $input->productor->renglonProductivo;
        $productor->tipo_productor_id = $input->productor->tipo;
        
        $productor->save();

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
        $economia->ingreso_familiar = $input->$economia->ingresoMensual;
        $economia->ingreso_agropecuaria = $input->$economia->ingresoAgropecuaria;
        $economia->personas_dependientes = $input->$economia->personasCargo;
        $economia->credito_id = $input->$economia->procedenciaCredito;
        $economia->otro_credito = $input->$economia->otroCredito;

        $economia->save();

        $innovacion = new Innovacion();
        $innovacion->productor_id     = $productor->id;
        //$innovacion->tipo_id    = $input->$innovacion->



        
        


        echo "ok";
    }
}