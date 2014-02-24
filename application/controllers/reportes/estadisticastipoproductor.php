<?php

class Estadisticastipoproductor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index() {

        $data = array();

        $query = "  SELECT avg(valor), renglonproductivo.id
                    FROM tp_c_respuesta
                    INNER JOIN visita_tipo_productor ON tp_c_respuesta.visita_id = visita_tipo_productor.id
                    INNER JOIN ruat ON ruat.id = visita_tipo_productor.ruat_id
                    INNER JOIN productor ON productor.id = ruat.productor_id
                    INNER JOIN renglonproductivo ON productor.renglon_productivo_id = renglonproductivo.id
                    WHERE pregunta_c_id = :var
                    GROUP BY renglonproductivo.id
                    ORDER BY descripcion
        ";

        $id_reporte1 = array(':var' => 13);
        $id_reporte2 = array(':var' => 19);
        $id_reporte3 = array(':var' => 25);
        $id_reporte4 = array(':var' => 26);

        $promedio_activos = TipoProductor::connection()->query($query, $id_reporte1);
        $promedio_ingresos = TipoProductor::connection()->query($query, $id_reporte2);
        $promedio_egresos = TipoProductor::connection()->query($query, $id_reporte3);
        $promedio_utilidad = TipoProductor::connection()->query($query, $id_reporte4);

        function to_array($model) {
            return $model->to_array();
        }

        $renglones_productivos = array_map('to_array', RenglonProductivo::sorted());

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

        $this->twiggy->set('renglones_productivos', $renglones_productivos);
        $this->twiggy->set('data', $data);

        $this->twiggy->template("reportes/estadisticastipoproductor");
        $this->twiggy->display();
    }

}
