<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Creditoagropecuario extends CI_Controller {

    public function __construct() {
        parent::__construct();

        //check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
    }

    public function index($ruat_id = NULL) {

        function to_array($model) {
            return $model->to_array();
        }

        $ruat = Ruat::find($ruat_id);

        if (!$ruat)
            show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div><label class="error">', '</label></div>');

        $productor = $ruat->productor;
        $contacto = $productor->contacto;

        $data = array();

        $data['tiposDocumento'] = array_map('to_array', TipoDocumento::sorted());

        $deptos = Departamento::all(array('order' => 'nombre', 'include' => array('municipios')));
        $deptos_municipios = array();
        foreach ($deptos as $depto) {
            $deptos_municipios[$depto->id] = array('nombre' => $depto->nombre, 'municipios' => array());
            foreach ($depto->municipios as $mun)
                $deptos_municipios[$depto->id]['municipios'][] = array('id' => $mun->id, 'nombre' => $mun->nombre);
        }

        $data['departamentos'] = $deptos_municipios;


        $solicitud_credito = SolicitudCredito::first(array(
                    'conditions' => array("ruat_id = ?", $ruat_id)
        ));
        

        $conyugue = $solicitud_credito->conyugue;
        $referencias_familiares = $solicitud_credito->referencias_familiares;
        $referencias_personales = $solicitud_credito->referencias_personales;
        $referencias_financieras = $solicitud_credito->referencias_financieras;
        $referencias_comerciales = $solicitud_credito->referencias_comerciales;
        $descripcion_inversiones = $solicitud_credito->descripcion_inversiones;
        $informacion_predios_inversion = $solicitud_credito->informacion_predios_inversion;
        $ingresos_adicionales = $solicitud_credito->ingresos_adicionales;
        $descripcion_bienes = $solicitud_credito->descripcion_bienes;
        

        $this->validation_rules();

        ///Si las validaciones son correctas procedo a guardar
        if ($this->form_validation->run()) {
            
            $solicitud_credito = ($solicitud_credito) ? $solicitud_credito : new SolicitudCredito();
            
            $solicitud_credito->ruat_id = $ruat_id;
//            $solicitud_credito->fecha = $this->input->post('sc_fecha');
            //$solicitud_credito->cod_beneficiario = null;
            //$solicitud_credito->nombre_oficina = null;
            $solicitud_credito->municipio = 1; /// OJO! no está aun en el formulario
            $solicitud_credito->experiencia = TRUE; /// OJO! no está aun en el formulario
            $solicitud_credito->calidad_de = TRUE; /// OJO! no está aun en el formulario
//            $solicitud_credito->rubros_fin_icr = TRUE;
//            $solicitud_credito->rubros_fin_dre = TRUE;
//            $solicitud_credito->descripcion_inv = TRUE;
//            $solicitud_credito->forma_llegar_pred = TRUE;
//            $solicitud_credito->tiempo_permanencia = TRUE;
//            $solicitud_credito->experiencia_act = TRUE;
            $solicitud_credito->responsable = 1; ///OJO! Poner el usuario
            
            if($solicitud_credito->save()){
                $conyugue = ($conyugue) ? $conyugue : new Conyugue();
                $conyugue->solicitud_id = $solicitud_credito->id;
                $conyugue->nombre1 = $this->input->post('conyugue_nombre1');
                $conyugue->nombre2 = $this->input->post('conyugue_nombre2');
                $conyugue->apellido1 = $this->input->post('conyugue_apellido1');
                $conyugue->apellido2 = $this->input->post('conyugue_apellido2');
                $conyugue->tipo_documento = $this->input->post('tipo_documento_conyugue');
                $conyugue->identificacion = $this->input->post('conyugue_identificacion');
                $conyugue->fecha_nacimiento = $this->input->post('conyugue_fecha_nacimiento');
                $conyugue->telefono = $this->input->post('conyugue_telefono');
                
                $conyugue->save();
                
                
                
            }
            
            
            
        }else if (validation_errors()) {
            
            var_dump(validation_errors());
            $this->twiggy->set('notif', array('type' => 'error', 'text' => "Se encontraron errores al procesar el formulario. <br> Revise los recuadros rojos"));
        }

        $this->twiggy->set('combos', $data);
        $this->twiggy->set("productor", $productor);
        $this->twiggy->set("contacto", $contacto);
        $this->twiggy->set("contacto_departamento", $contacto->departamento);
        $this->twiggy->set("contacto_municipio", $contacto->municipio);
        
       
        $this->twiggy->set("conyugue", $conyugue);
        $this->twiggy->set("referencias_familiares", $referencias_familiares);
        $this->twiggy->set("referencias_personales", $referencias_personales);
        $this->twiggy->set("referencias_financieras", $referencias_financieras);
        $this->twiggy->set("referencias_comerciales", $referencias_comerciales);
        $this->twiggy->set("descripcion_inversiones", $descripcion_inversiones);
        $this->twiggy->set("informacion_predios_inversion", $informacion_predios_inversion);
        $this->twiggy->set("ingresos_adicionales", $ingresos_adicionales);
        $this->twiggy->set("descripcion_bienes", $descripcion_bienes);

        $this->twiggy->template("creditoagropecuario/formulario_captura");
        $this->twiggy->display();
    }
    
    private function validation_rules(){
        $this->form_validation->set_rules("conyugue_nombre1", ' ', 'required');
    }

    public function imprimible($ruat_id = NULL) {

        $ruat = Ruat::find($ruat_id);

        if (!$ruat)
            show_404();

        $productor = $ruat->productor;
        $contacto = $productor->contacto;
        $renglon = $productor->renglon_productivo;
        $vtp = VisitaTipoProductor::first(array(
                    'conditions' => array('ruat_id = ?', $ruat_id)
        ));


        ///consulto las preguntas C para cargarlas dinamicamente
        $preguntas_c = TPCPregunta::all(array('order' => 'categoria, ordenamiento'));

        ///consulo las respuestas C
        $respuestas_c = TPCRespuesta::all(array(
                    'conditions' => array('visita_id = ?', $vtp->id)
        ));



        ///acomodo las respuestas con la pregunta como llave
        $respuestas_c_aux = array();
        foreach ($respuestas_c as $obj)
            $respuestas_c_aux[$obj->pregunta_c_id] = $obj;

        ///acomodo las preguntas C por categoria
        $preguntas_ingresos = $preguntas_egresos = $preguntas_activos = $preguntas_totales = array();
        foreach ($preguntas_c as $obj) {

            $objAux = $obj->to_array();

            ///Si la pregunta C tiene respuesta, la agrego al objeto de la pregunta
            if (isset($respuestas_c_aux[$obj->id]))
                $objAux['respuesta_bd'] = $respuestas_c_aux[$obj->id]->valor;

            if ($obj->categoria === 'A')
                $preguntas_activos[] = $objAux;
            elseif ($obj->categoria === 'B')
                $preguntas_ingresos[] = $objAux;
            elseif ($obj->categoria === 'C')
                $preguntas_egresos[] = $objAux;
            elseif ($obj->categoria === 'D')
                $preguntas_totales[] = $objAux;
        }

        $this->twiggy->set("productor", $productor);
        $this->twiggy->set("contacto", $contacto);
        $this->twiggy->set("contacto_departamento", $contacto->departamento);
        $this->twiggy->set("contacto_municipio", $contacto->municipio);
        $this->twiggy->set("renglon", $renglon);

        $this->twiggy->set('preguntas_ingresos', $preguntas_ingresos);
        $this->twiggy->set('preguntas_egresos', $preguntas_egresos);
        $this->twiggy->set('preguntas_activos', $preguntas_activos);
        $this->twiggy->set('preguntas_totales', $preguntas_totales);

//        echo '<pre>';
//        var_dump($preguntas_egresos);
//        echo '</pre>';

        $this->twiggy->template("creditoagropecuario/formulario_captura_imprimible");
        $this->twiggy->display();
    }

    public function guardar() {
        $input = json_decode(file_get_contents("php://input"));

        if (empty($input->ruat_id)) {
            if ($this->documento_usado($input->productor->numero_documento)) {
                echo json_encode(array('success' => false, 'message' => array('type' => 'error',
                        'text' => 'Fallo al Guardar RUAT. <br> Ya hay un productor registrado con este número de documento')));
                return;
            }

            $ruat = new Ruat;
            $ruat->numero_formulario = $input->numero_formulario;
            $ruat->creador_id = current_user('id');
            $productor = Productor::create((array) $input->productor);
            $ruat->productor_id = $productor->id;
            $ruat->save();
        } else {
            if ($this->documento_usado($input->productor->numero_documento, $input->productor->id)) {
                echo json_encode(array('success' => false, 'message' => array('type' => 'error',
                        'text' => 'Fallo al Guardar RUAT. <br> Hay otro productor registrado con este número de documento')));
                return;
            }
            $ruat = Ruat::find($input->ruat_id);
            $ruat->numero_formulario = $input->numero_formulario;
            $ruat->modificado = time();
            $ruat->modificador_id = current_user('id');
            $ruat->save();
            $productor = $ruat->productor;
            $productor->set_attributes((array) $input->productor);
            $productor->save();
        }

        $input->contacto->productor_id = $productor->id;
        Contacto::create_or_update((array) $input->contacto);

        $econo = $input->economia;
        if (!$econo->usaCredito)
            $econo->credito_id = null;
        if ($econo->credito_id != 7)
            $econo->otro_credito = null;
        unset($econo->usaCredito);
        $econo->productor_id = $productor->id;
        Economia::create_or_update((array) $econo);

        Innovacion::table()->delete(array('ruat_id' => $ruat->id));
        foreach ($input->innovaciones as $innova) {
            if (!$innova->fuente_id)
                continue;
            $innova->ruat_id = $ruat->id;
            if ($innova->fuente_id != 6)
                $innova->otra_fuente = null;
            Innovacion::create((array) $innova);
        }

        Orgasociada::table()->delete(array('ruat_id' => $ruat->id));
        RazonNoPertenecer::table()->delete(array('ruat_id' => $ruat->id));

        foreach ($input->asociacion->cooperativa->filas as $org) {
            $clases = $org->clases;
            $beneficios = $org->beneficios;
            $directivo = $org->directivo;
            unset($org->clases, $org->beneficios, $org->directivo);
            $org->ruat_id = $ruat->id;
            $org->membresia = $org->directivo ? 'Directivo' : 'Participante';

            $orgasociada = Orgasociada::create((array) $org);

            foreach ($clases as $cls)
                OrgasociadaClase::create(array(
                    'orgasociada_id' => $orgasociada->id, 'clase_id' => $cls));

            foreach ($beneficios as $bnf)
                OrgasociadaBeneficio::create(array(
                    'orgasociada_id' => $orgasociada->id, 'beneficio_id' => $bnf));
        }

        foreach ($input->asociacion->cooperativa->razones as $razon)
            RazonNoPertenecer::create(array('ruat_id' => $ruat->id, 'razon_id' => $razon));

        $ruat->orgs_apoyan = json_encode($input->asociacion->cooperativa->orgs_apoyan);

        if ($input->asociacion->otroProductor->asociado) {
            $asoc = $input->asociacion->otroProductor;
            unset($asoc->asociado);
            $per = PersonaAsociada::create_or_update((array) $asoc);
            $ruat->asociado_id = $per->id;
        } else if ($ruat->asociado_id) {
            $id_to_delete = $ruat->asociado_id;
            $ruat->asociado_id = null;
            $ruat->save();
            PersonaAsociada::table()->delete(array('id' => $id_to_delete));
        }

        if ($input->asociacion->sigue->asociado) {
            $asoc = $input->asociacion->sigue;
            unset($asoc->asociado);
            $per = PersonaAsociada::create_or_update((array) $asoc);
            $ruat->seguir_id = $per->id;
        } else if ($ruat->seguir_id) {
            $id_to_delete = $ruat->seguir_id;
            $ruat->seguir_id = null;
            $ruat->save();
            PersonaAsociada::table()->delete(array('id' => $id_to_delete));
        }

        $ruat->save();
        $response = array(
            'success' => true,
            'message' => array('type' => 'success', 'text' => 'Guardado Exitoso <br/> Cargando siguiente sección...'),
            'scope' => $this->cargar($ruat->id)
        );

        echo json_encode($response);
    }

    private function documento_usado($cedula, $productor_id = NULL) {
        $cond = $productor_id ? array("numero_documento = ? and id <> ?", $cedula, $productor_id) : array("numero_documento = ?", $cedula);

        return Productor::exists(array('conditions' => $cond));
    }

    public function cargar($ruat_id, $do_echo = false) {
        $ruat = Ruat::find($ruat_id);
        $output = new StdClass;
        $output->soloLectura = $ruat->soloLectura($this);

        $output->ruat_id = $ruat->id;
        $output->numero_formulario = $ruat->numero_formulario;
        $output->productor = $ruat->productor->to_array();
        $output->productor['fecha_nacimiento'] = $this->datefmt($ruat->productor->fecha_nacimiento);
        $output->contacto = $ruat->productor->contacto->to_array();
        $output->economia = $ruat->productor->economia->to_array();
        $output->economia['usaCredito'] = (bool) ($output->economia['credito_id']);
        $output->asociacion = array(
            'cooperativa' => array('filas' => array()),
            'otroProductor' => array('asociado' => false),
            'sigue' => array('asociado' => false),
        );

        $coops = Orgasociada::find_all_by_ruat_id($ruat->id, array('include' => array('clases', 'beneficios')));
        foreach ($coops as $org) {
            $orgasociada = $org->to_array();
            $orgasociada['directivo'] = $orgasociada['membresia'] == 'Directivo';
            $orgasociada['clases'] = extract_prop($org->clases, 'clase_id');
            $orgasociada['beneficios'] = extract_prop($org->beneficios, 'beneficio_id');
            $output->asociacion['cooperativa']['filas'][] = $orgasociada;
        }


        if (!count($output->asociacion['cooperativa']['filas'])) {
            $output->asociacion['cooperativa']['filas'][] = new StdClass; //filita vacia
            $output->asociacion['cooperativa']['asociado'] = false;
        } else
            $output->asociacion['cooperativa']['asociado'] = true;

        $output->asociacion['cooperativa']['orgs_apoyan'] = json_decode($ruat->orgs_apoyan);
        $output->asociacion['cooperativa']['razones'] = extract_prop(RazonNoPertenecer::find_all_by_ruat_id($ruat->id), 'razon_id');

        if ($ruat->asociado_id) {
            $output->asociacion['otroProductor'] = PersonaAsociada::find($ruat->asociado_id)->to_array();
            $output->asociacion['otroProductor']['asociado'] = true;
        }
        if ($ruat->seguir_id) {
            $output->asociacion['sigue'] = PersonaAsociada::find($ruat->seguir_id)->to_array();
            $output->asociacion['sigue']['asociado'] = true;
        }

        $inno_map = array();
        foreach (Innovacion::find_all_by_ruat_id($ruat->id) as $inno) {
            $inno_map[$inno->tipo_id] = $inno->to_array();
        }

        $output->innovaciones = array();
        $output->realizaInnovacion = false;
        foreach (TipoInnovacion::sorted() as $t) {
            if (isset($inno_map[$t->id])) {
                $output->innovaciones[] = $inno_map[$t->id];
                $output->realizaInnovacion = true;
            } else
                $output->innovaciones[] = array('tipo_id' => $t->id);
        }

        if ($do_echo)
            echo json_encode($output);
        return $output;
    }

    private function datefmt($f) {
        return $f ? $f->format('Y-m-d') : '';
    }

}
