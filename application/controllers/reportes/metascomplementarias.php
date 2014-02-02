<?php

class Metascomplementarias extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        /*$informes = array(
            array('url' => 'informes/prueba', 'title' => 'Reportico', 'icon' => 'i-file-8', 'color' => 'green'),
            array('url' => 'informes/ruatImprimible', 'title' => 'Imprimir Ruat', 'icon' => 'i-print', 'color' => 'blue'),
            array('url' => 'informes/oto', 'title' => 'Oto Reporte', 'icon' => 'i-file-7', 'color' => 'red')

        );

        $this->twiggy->set('informes', $informes);*/

        $this->load->library('form_validation');
        $preguntas = MetaComplementariaPregunta::sorted();

        foreach($preguntas as $pregunta){
            $this->form_validation->set_rules('fila'.$pregunta->orden, 'Concepto '. $pregunta->orden);
            $this->form_validation->set_rules('total'.$pregunta->orden, 'Total '. $pregunta->orden);
            $this->form_validation->set_rules('porcentaje'.$pregunta->orden, 'Porcentaje '. $pregunta->orden);
            for($i = 0;$i<8;$i++){
                $this->form_validation->set_rules('mes'.$pregunta->orden.$i, 'Mes '. $pregunta->orden);
            }
            
        }

        if ($this->form_validation->run()) {
        }

        
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('tamaño', count($preguntas));

        $this->twiggy->template("reportes/metas");
        $this->twiggy->display();
        
    }
}