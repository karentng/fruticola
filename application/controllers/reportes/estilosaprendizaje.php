<?php

class Estilosaprendizaje extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }
    
    public static function to_array($model){
        return $model->to_array();
    }

    public function index() {


        $preguntas = array_map(array('Estilosaprendizaje', 'to_array'), TipoPregunta::sorted());
        $respuestas = array_map(array('Estilosaprendizaje', 'to_array'), TipoRespuesta::sorted());

        $municipios = array_map(array('Estilosaprendizaje', 'to_array'), Municipio::find_all_by_departamento_id(30, array('select' => 'id,nombre', 'order' => 'nombre')));
        $renglonesProductivos = array_map(array('Estilosaprendizaje', 'to_array'), RenglonProductivo::sorted());

        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('renglonesProductivos', $renglonesProductivos);
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('respuestas', $respuestas);

        $this->twiggy->template("reportes/estilosaprendizaje");
        $this->twiggy->display();
    }

    public function tabla() {

        $renglon = $this->input->post('renglon_productivo');
        $municipios = $this->input->post('municipios');

        $preguntas = array_map(array('Estilosaprendizaje', 'to_array'), TipoPregunta::sorted());
        $respuestas = array_map(array('Estilosaprendizaje', 'to_array'), TipoRespuesta::sorted());

        if (!$municipios)
            $municipios = array();
        
        if (!$renglon)
            $renglon = array();

        $matriz_promedios = $this->consultarDatos($preguntas, $renglon, $municipios);
        $this->twiggy->set('matriz_promedios', $matriz_promedios);
        $this->twiggy->set('preguntas', $preguntas);
        $this->twiggy->set('respuestas', $respuestas);

        $this->twiggy->register_function('number_format');
        $this->twiggy->template("reportes/estilosaprendizajetabla");
        $this->twiggy->display();
    }

    private function consultarDatos($preguntas, $renglones, $municipios = array()) {


        $arr_condiciones = array();///arreglo para luego "bindear" las variables al sql
        $matriz_promedios = array();
        
        ///variable aux para guardar el sql de la condicion del municipio
        $aux = array();
        /// para cada municipio creo una entrada en el arreglo $arr_condiciones
        foreach ($municipios as $i => $municipio) {
            $arr_condiciones[":municipio_{$i}"] = (int) $municipio['id'];
            $aux[] = "finca.municipio_id = :municipio_{$i}";
        }
        ///Contcateno los condicionales individuales
        $where_municipio = '';
        if (!empty($aux)) {
            $where_municipio = ' AND ( ' . implode(' OR ', $aux) . ' )';
        }      
        
        
        ///variable aux para guardar el sql de la condicion del renglon
        $aux = array();
        /// para cada renglon creo una entrada en el arreglo $arr_condiciones
        foreach ($renglones as $i => $renglon) {
            $arr_condiciones[":renglon_{$i}"] = (int) $renglon['id'];
            $aux[] = "productor.renglon_productivo_id = :renglon_{$i}";
        }
        ///Contcateno los condicionales individuales
        $where_renglon = '';
        if (!empty($aux)) {
            $where_renglon = ' AND ( ' . implode(' OR ', $aux) . ' )';
        }   


        $query = "  SELECT 
	aprendizaje_respuesta.respuesta_id, 
	count(aprendizaje_respuesta.ruat_id)
        FROM aprendizaje_respuesta 
        JOIN ruat ON (ruat.id = aprendizaje_respuesta.ruat_id)
        JOIN finca ON finca.ruat_id = ruat.id
        JOIN productor ON ruat.productor_id = productor.id
        WHERE pregunta_id = :var $where_municipio $where_renglon
        GROUP BY respuesta_id
        ORDER BY respuesta_id ";

        for ($i = 1; $i <= count($preguntas); $i++) {
            $id_preguntas[$i] = array(':var' => $i);
        }

        foreach ($id_preguntas as $id_pregunta) {
            
            ///agrego la condicion de la pregunta al resto de condiciones
            $tmp_condiciones = array_merge($arr_condiciones, $id_pregunta);
            
            $tmp_query_result = AprendizajeRespuesta::connection()->query($query, $tmp_condiciones);
            $matriz_promedios[$id_pregunta[':var'] - 1] = array(0, 0, 0, 0, 0);
            $num_column = 0;

            foreach ($tmp_query_result as $column) {

                $matriz_promedios[$id_pregunta[':var'] - 1][$num_column] = $column['count'];
                $num_column++;
            }
        }

        return $matriz_promedios;
    }

}
