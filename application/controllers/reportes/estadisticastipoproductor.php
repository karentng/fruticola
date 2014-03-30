<?php

class Estadisticastipoproductor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index() {

        function to_array($model) { return $model->to_array(); }

        $municipios = array_map('to_array', Municipio::find_all_by_departamento_id(30, array('select' => 'id,nombre', 'order' => 'nombre')));
        $renglones_productivos = array_map('to_array', RenglonProductivo::sorted());

        $this->twiggy->set('renglones_productivos', $renglones_productivos);
        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->template("reportes/estadisticastipoproductor");
        $this->twiggy->display();
    }
    
    public function tabla() {

        function to_array1($model) { return $model->to_array(); }

        $data = array();
        $municipios = $this->input->post('municipios');
        
        if (!$municipios)
            $municipios = array();

        $renglones_productivos = array_map('to_array1', RenglonProductivo::sorted());

        $data = $this->consultarDatos($municipios);
        
        $this->twiggy->set('renglones_productivos', $renglones_productivos);
        $this->twiggy->set('data', $data);
        
        $this->twiggy->register_function('number_format');
        $this->twiggy->template("reportes/estadisticastipoproductortabla");
        $this->twiggy->display();
    }
    
    private function consultarDatos($municipios = array()) {

        function to_array2($model) { return $model->to_array(); }

        $data = array();

        $aux = array();
        $arr_condiciones = array();
        foreach ($municipios as $i => $municipio) {
            $arr_condiciones[":municipio_{$i}"] = (int) $municipio['id'];
            $aux[] = "finca.municipio_id = :municipio_{$i}";
        }

        $where_municipio = '';
        if (!empty($aux)) {
            $where_municipio = ' AND ( ' . implode(' OR ', $aux) . ' )';
        }

        $query = "  SELECT avg(valor), renglonproductivo.id
                    FROM tp_c_respuesta
                    INNER JOIN visita_tipo_productor ON tp_c_respuesta.visita_id = visita_tipo_productor.id
                    INNER JOIN ruat ON ruat.id = visita_tipo_productor.ruat_id
                    INNER JOIN finca ON finca.ruat_id = ruat.id
                    INNER JOIN productor ON productor.id = ruat.productor_id
                    INNER JOIN renglonproductivo ON productor.renglon_productivo_id = renglonproductivo.id
                    WHERE pregunta_c_id = :var $where_municipio
                    GROUP BY renglonproductivo.id
                    ORDER BY descripcion
        ";

        $tmp_condiciones1 = array_merge($arr_condiciones, array(':var' => 13));
        $tmp_condiciones2 = array_merge($arr_condiciones, array(':var' => 19));
        $tmp_condiciones3 = array_merge($arr_condiciones, array(':var' => 25));
        $tmp_condiciones4 = array_merge($arr_condiciones, array(':var' => 26));

        $promedio_activos = TipoProductor::connection()->query($query, $tmp_condiciones1);
        $promedio_ingresos = TipoProductor::connection()->query($query, $tmp_condiciones2);
        $promedio_egresos = TipoProductor::connection()->query($query, $tmp_condiciones3);
        $promedio_utilidad = TipoProductor::connection()->query($query, $tmp_condiciones4);

        $renglones_productivos = array_map('to_array2', RenglonProductivo::sorted());

        $row1 = $row2 = $row3 = $row4 = null;

        $data[0][0] = "1.Promedio de activos por productor anual";
        $data[1][0] = "2.Promedio de ingresos por productor anual";
        $data[2][0] = "3.Promedio de egresos por productor anual";
        $data[3][0] = "4.Promedio de utilidad por productor anual";


        foreach ($renglones_productivos as $renglon_productivo) {
            $renglon_id = $renglon_productivo['id'];

            ///Inicializo en 0
            $data[0][$renglon_id] = 0;
            $data[1][$renglon_id] = 0;
            $data[2][$renglon_id] = 0;
            $data[3][$renglon_id] = 0;
        }

        ///cargo desde la consulta los promedios
        foreach ($promedio_activos as $column) {
            $id = $column['id'];
            $data[0][$id] =  number_format($column['avg'], 2, ',', '.');
        }

        ///cargo desde la consulta los promedios
        foreach ($promedio_ingresos as $column) {
            $id = $column['id'];
            $data[1][$id] = number_format($column['avg'], 2, ',', '.');;
        }

        ///cargo desde la consulta los promedios
        foreach ($promedio_egresos as $column) {
            $id = $column['id'];
            $data[2][$id] = number_format($column['avg'], 2, ',', '.');;
        }

        ///cargo desde la consulta los promedios
        foreach ($promedio_utilidad as $column) {
            $id = $column['id'];
            $data[3][$id] = number_format($column['avg'], 2, ',', '.');;
        }

        return $data;

    }

}
