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

        $beneficios_sociedad = array("economico"=>"Económico","especie"=>"En especie","capacitacion"=>"Capacitación"
                                    ,"recreacion"=>"Recreación y Deporte","reconocimiento"=>"Reconocimiento de la Comunidad"
                                    ,"participacion_decisiones"=>"Participación en la Toma de Decisiones","otro"=>"Otro");
        $periodicidad = array("semanal"=>"Semanal", "quincenal"=>"Quincenal", "mensual"=>"Mensual", "bimestral"=>"Bimestral", 
                            "trimestral"=>"Trimestral", "semestral"=>"Semestral", "anual"=>"Anual");

        $this->twiggy->set('beneficios_sociedad',$beneficios_sociedad);
        $this->twiggy->set('periodicidad',$periodicidad);


        
        $this->load->library('form_validation');
        //poner validaciones aqui. Ejemplo:
        //$this->form_validation->set_rules('name','Nombre', 'required|max_length[50]');

        $this->form_validation->set_rules('beneficios_sociedad','Beneficios de Sociedades, Cooperativas o Gremios','required');
        $this->form_validation->set_rules('periodicidad1');
        $this->form_validation->set_rules('periodicidad2');
        $this->form_validation->set_rules('periodicidad3');


        if($this->form_validation->run())
        {
            //las validaciones pasaron, aqui iria la logica de insertar en la BD...
        }

        $data = array();  // ----- que habia pasado con esto?????
        $data['tiposDocumento'] = assoc(TipoDocumento::sorted());
        $data['nivelesEducativos'] = assoc(NivelEducativo::sorted());
        $data['tiposProductor'] = assoc(TipoProductor::sorted());
        $data['renglonesProductivos'] = assoc(RenglonProductivo::sorted());
        //var_dump($tiposDocumento);
        //die();

        $this->twiggy->set($data, NULL);
        $this->twiggy->template("ruat/datos_personales");
        $this->twiggy->display();
    }
}