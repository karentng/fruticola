<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Certificacionvisita extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
    }

    public function index($ruat_id, $formulario) //= 1795
    {
        if($formulario < 0 || $formulario > 10){
            show_404();
        }
        if(!$ruat_id) show_404();

        /*$preguntas = TPCPregunta::all(array('order' => 'categoria, ordenamiento'));
        
        $preguntas_ingresos = $preguntas_egresos = $preguntas_activos = $preguntas_totales = array();
        
        foreach ($preguntas as $obj) {
            if($obj->categoria === 'A')
                $preguntas_activos[] = $obj->to_array();
            elseif($obj->categoria === 'B')
                $preguntas_ingresos[] = $obj->to_array();
            elseif($obj->categoria === 'C')
                $preguntas_egresos[] = $obj->to_array();
            elseif($obj->categoria === 'D')
                $preguntas_totales[] = $obj->to_array();
        }*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('fecha', 'Fecha', 'required');
        $this->form_validation->set_rules('descripcion', 'Descripción');
        $this->form_validation->set_rules('observaciones', 'Observaciones');

        if ($this->form_validation->run()) {
            $certificacion = CertificacionVisit::find_by_ruat_id_and_num_formulario($ruat_id, $formulario);
            if($certificacion){
                $certificacion->delete();
            }
            $certificacion = new CertificacionVisit();
            $certificacion->ruat_id = $ruat_id;
            $certificacion->num_formulario = $formulario;
            $certificacion->fecha = $this->input->post('fecha');
            $certificacion->descripcion = $this->input->post('descripcion');
            $certificacion->observaciones = $this->input->post('observaciones');
            $certificacion->save();
        }

        
        //$this->twiggy->register_function('var_dump');
        $titulo = "";
        switch($formulario){
            case 0:
                $titulo = "Elaboración de propuestas asociativas para estudio de riego";
                break;
            case 1:
                $titulo = "Implementación de prácticas de manejo agronómico tendientes a romper la estacionalidad";
                break;
            case 2:
                $titulo = "Renovación de cultivos existentes y plantación de áreas nuevas con variedades promisorias";
                break;
            case 3:
                $titulo = "Mejoramiento de las áreas actuales con semilla de PIÑA de alta calidad genética y productiva";
                break;
            case 4:
                $titulo = "Implementación de modelos de producción óptima de MANGO";
                break;
            case 5:
                $titulo = "Mejoramiento de las áreas actuales con semilla de FRESA de alta calidad genética y productiva";
                break;
            case 6:
                $titulo = "Mejoramiento de las áreas actuales con semilla de MORA de alta calidad genética y productiva";
                break;
            case 7:
                $titulo = "Planes de fertilidad";
                break;
            case 8:
                $titulo = "Visitas de seguimiento y/o acompañamiento";
                break;
            case 9:
                $titulo = "Elaboración de los planes de negocio regional";
                break;
            case 10:
                $titulo = "Incremento de los rendimientos de cultivo";
                break;
        }

        $ruat = Ruat::find_by_id($ruat_id);
        $finca = Finca::find_by_ruat_id($ruat->id);
        $municipio = Municipio::find_by_id($finca->municipio_id);
        $productor = Productor::find_by_id($ruat->productor_id);
        $renglon = Renglonproductivo::find_by_id($productor->renglon_productivo_id);
        $contacto = Contacto::find_by_productor_id($productor->id);

        $this->twiggy->set('titulo', $titulo);
        $this->twiggy->set('productor', $productor->to_array());
        $this->twiggy->set('finca', $finca->to_array());
        $this->twiggy->set('municipio', $municipio->to_array());
        $this->twiggy->set('renglon', $renglon->to_array());
        $this->twiggy->set('num_formulario', $ruat->numero_formulario);
        $this->twiggy->set('contacto', $contacto->to_array());

        $certificacion = CertificacionVisit::find_by_ruat_id_and_num_formulario($ruat_id, $formulario);
        if($certificacion){
            if($certificacion->fecha == "" || $certificacion->fecha == null){
                $this->twiggy->set('fechaI', "");
            }else{
                $this->twiggy->set('fechaI', $certificacion->fecha->format("Y-m-d"));
            }
            $this->twiggy->set('descripcionI', $certificacion->descripcion);
            $this->twiggy->set('observacionesI', $certificacion->observaciones);
        }

        $this->twiggy->template("certificacionvisita/cetificacion_visita");
        $this->twiggy->display();
    }
}