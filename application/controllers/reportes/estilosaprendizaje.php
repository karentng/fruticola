<?php

class Estilosaprendizaje extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index() {

        function to_array($model) {
            return $model->to_array();
        }

        $data = array();
        $matriz_promedios = array();
        $preguntas = array_map('to_array', TipoPregunta::sorted());
        $respuestas = array_map('to_array', TipoRespuesta::sorted());

        $query = "  SELECT respuesta_id, count(ruat_id)
                    FROM aprendizaje_respuesta
                    WHERE pregunta_id = :var
                    GROUP BY respuesta_id
                    ORDER BY respuesta_id ";

        for( $i = 1; $i <= count($preguntas); $i++ ) {
            $id_preguntas[$i] = array( ':var' => $i );
        }

        foreach ($id_preguntas as $id_pregunta) {
            
            $tmp_query_result = AprendizajeRespuesta::connection()->query( $query, $id_pregunta );
            $matriz_promedios[$id_pregunta[':var']-1] = array( 0, 0, 0, 0, 0 );
            $num_column = 0;
            
            foreach ($tmp_query_result as $column) {
            
                $matriz_promedios[$id_pregunta[':var']-1][$num_column] = $column['count'];
                $num_column++;
            }
        }

        /*echo("<pre>");
        var_dump($matriz_promedios);
        echo("</pre>");
        die;*/

        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('respuestas', $respuestas);

        $this->twiggy->set('matriz_promedios', $matriz_promedios);
        $this->twiggy->set('data', $data);

        $this->twiggy->template("reportes/estilosaprendizaje");
        $this->twiggy->display();
    }

}
