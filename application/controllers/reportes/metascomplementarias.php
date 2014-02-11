<?php

class Metascomplementarias extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
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
                $this->form_validation->set_rules('mes'.$pregunta->orden.$i, 'Mes '. $pregunta->orden, 'required');
            }
            
        }

        $existe = false;
        $metas = MetaComplementaria::find('all');
        
        if(count($metas) > 0){
            $existe = true;
        }

        if ($this->form_validation->run()) {

            for($i = 0; $i < count($preguntas) ; $i++){
                $meta;
                if($existe){
                    $meta = $metas[$i];
                }else{
                    $meta = new MetaComplementaria();
                    $meta->fila = $i;
                }

                $meta->total = $this->input->post('total'.$preguntas[$i]->orden);
                $meta->porcentaje = $this->input->post('porcentaje'.$preguntas[$i]->orden);
                $meta->save();

                if($existe){// mes 1 0-->mes 1 7  mes 2 0-->mes 2 7....
                    $respuestas = MetaComplementariaRespuesta::find('all', array('order' => 'id', 'conditions' => array('pregunta = ?', $preguntas[$i]->id)));
                    for($j = 0; $j < count($respuestas) ; $j++){
                        $respuesta = $respuestas[$j];
                        //$respuesta->meta_id = $meta->id;
                        //$respuesta->pregunta = $preguntas[$i]->id;

                        $respuesta->valor = $this->input->post('mes'.$preguntas[$i]->orden.$j);

                        
                        //$respuesta->mes = $j;
                        
                        $respuesta->save();
                    }
                }else{
                    for($j = 0; $j < 8 ; $j++){
                        $respuesta = new MetaComplementariaRespuesta();
                        $respuesta->meta_id = $meta->id;
                        $respuesta->pregunta = $preguntas[$i]->id;
                        $respuesta->valor = $this->input->post('mes'.$preguntas[$i]->orden.$j);
                        //$respuesta->mes = $j;
                        $respuesta->save();
                    }
                    
                }
            }

            // Todo bien, para donde lo mando? :D
        }

        if($existe){
            $valoresIntermedios = array();
            $valoresFinales = array();

            for($i = 0 ; $i < count($metas) ; $i++){
                // valores del medio
                $aux = array();
                $valoresPorFila = MetaComplementariaRespuesta::find('all', array('order' => 'id', 'conditions' => array('meta_id = ?', $metas[$i]->id)));
                for($j = 0 ; $j <= count($valoresPorFila) ; $j++){
                    $aux[$j] = $valoresPorFila[$j]->valor;
                }
                array_push($valoresIntermedios, $aux);

                // valores definitivos
                $valoresFinales[$i]['total'] = $metas[$i]->total;
                $valoresFinales[$i]['porcentaje'] = $metas[$i]->porcentaje;
            }

            $this->twiggy->set('valoresIntermedios', $valoresIntermedios);
            $this->twiggy->set('valoresFinales', $valoresFinales);

            $this->twiggy->set('valoresFinalesJSON', json_encode($valoresFinales));
            $this->twiggy->set('valoresIntermediosJSON', json_encode($valoresIntermedios));
        }else{
            $valoresIntermedios = array();
            $valoresFinales = array();

            for($i = 0 ; $i < 14 ; $i++){
                // valores del medio
                $aux = array();
                for($j = 0 ; $j <= 7 ; $j++){
                    $aux[$j] = 0;
                }
                array_push($valoresIntermedios, $aux);

                // valores definitivos
                $valoresFinales[$i]['total'] = 0;
                $valoresFinales[$i]['porcentaje'] = 0;
            }

            $this->twiggy->set('valoresIntermedios', $valoresIntermedios);
            $this->twiggy->set('valoresFinales', $valoresFinales);

            $this->twiggy->set('valoresFinalesJSON', json_encode($valoresFinales));
            $this->twiggy->set('valoresIntermediosJSON', json_encode($valoresIntermedios));
        }
        
        $this->twiggy->set('preguntas', $preguntas);

        $result = array();
        foreach($preguntas as $pregunta){
            array_push($result, $pregunta->to_array());
        }

        $this->twiggy->set('preguntasJSON', json_encode($result));

        $this->twiggy->set('tamaño', count($preguntas));

        if(check_profile(array("Administrador","Coordinador"), false)){
            $this->twiggy->set('soloLectura', false);
        }else{
            $this->twiggy->set('soloLectura', true);
        }

        $this->twiggy->template("reportes/metas");
        $this->twiggy->display();
        
    }
}