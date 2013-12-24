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

                        
        $this->load->library('form_validation');
        //poner validaciones aqui. Ejemplo:
        //$this->form_validation->set_rules('name','Nombre', 'required|max_length[50]');

        $this->form_validation->set_rules('beneficiosSociedad', 'Beneficios de Sociedades','required');
        $this->form_validation->set_rules('tipoCredito', 'Procedencia del Crédito', 'required');
        $this->form_validation->set_rules('periodicidad1', 'Periodicidad', 'required');
        $this->form_validation->set_rules('periodicidad2', 'Periodicidad', 'required');
        $this->form_validation->set_rules('periodicidad3', 'Periodicidad', 'required');
        $this->form_validation->set_rules('tipo_documento', 'Tipo de Documento', 'required');
        $this->form_validation->set_rules('nivel_educativo', 'Nivel Educativo', 'required');
        $this->form_validation->set_rules('tipo_productor', 'Tipo de Productor', 'required');
        $this->form_validation->set_rules('renglon_productivo', 'Renglon Productivo', 'required');
        $this->form_validation->set_rules('clases_organizacion1', 'Clase de Organización', 'required');
        $this->form_validation->set_rules('clases_organizacion2', 'Clase de Organización', 'required');
        $this->form_validation->set_rules('clases_organizacion3', 'Clase de Organización', 'required');
        $this->form_validation->set_rules('gradoConfianza1', 'Grado de Confianza', 'required');
        $this->form_validation->set_rules('gradoConfianza2', 'Grado de Confianza', 'required');




        if($this->form_validation->run())
        {
            //las validaciones pasaron, aqui iria la logica de insertar en la BD...
        }

        $data = array();  // ----- que habia pasado con esto?????
        $data['tiposDocumento'] = assoc(TipoDocumento::sorted());
        $data['nivelesEducativos'] = assoc(NivelEducativo::sorted());
        $data['tiposProductor'] = assoc(TipoProductor::sorted());
        $data['renglonesProductivos'] = assoc(RenglonProductivo::sorted());
        $data['clasesOrganizaciones'] = assoc(ClaseOrganizacion::sorted());
        $data['beneficiosSociedad'] = assoc(TipoBeneficio::sorted());
        $data['tipoCredito'] = assoc(TipoCredito::sorted());
        $data['periodicidad'] = assoc(Periodicidad::sorted());
        $data['tipoConfianza'] = assoc(TipoConfianza::sorted());
        

        //var_dump($tiposDocumento);
        //die();

        $this->twiggy->set($data, NULL);
        $this->twiggy->template("ruat/datos_personales");
        $this->twiggy->display();
    }
}